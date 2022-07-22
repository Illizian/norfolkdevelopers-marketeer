<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Accounts\Twitter\TwitterAccount;
use App\Accounts\Twitter\TwitterCallback;
use App\Accounts\Twitter\TwitterRedirect;
use App\Accounts\Twitter\TwitterResponse;
use App\Models\Account;
use App\Models\Post;
use Twitter\Text\Parser;

use function Pest\Faker\faker;

it('returns the provider name', function () {
    expect(TwitterAccount::name())->toEqual('Twitter');
});

it('returns the provider fields', function () {
    expect(TwitterAccount::fields())->toEqual([]);
});

it('returns a status of PENDING by default for new accounts', function () {
    $subject = new Account();

    expect($subject->status())->toEqual('PENDING');
});

/**
 * public function redirect(): TwitterRedirect
 */
it('throws an Exception if TwitterOAuth fails to retrieve a token for the redirect', function () {
    // Mock the TwitterOAuth Library and assert it's usage
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)->expect(
            oauth: fn () => [],
            getLastHttpCode: fn () => 401,
        )
    );

    // Create an account, and assign the Twitter Provider to it.
    Account::factory()
        ->withProvider(TwitterAccount::class)
        ->create()
        ->getProvider()
        ->redirect();
})->throws(Exception::class, 'An error occured when creating an authorisation URL', 401);

it('returns a TwitterRedirect', function () {
    // Mock the TwitterOAuth Library and assert it's usage
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)->expect(
            oauth: fn () => [
                'oauth_token' => 'token',
                'oauth_token_secret' => 'secret'
            ],
            getLastHttpCode: fn () => 200,
            url: fn () => 'url',
        )
    );

    // Create an account, assign the Twitter Provider
    // and get an AccountRedirectInterface from it
    $subject = Account::factory()
        ->withProvider(TwitterAccount::class)
        ->create()
        ->getProvider()
        ->redirect();

    expect($subject)->toBeInstanceOf(TwitterRedirect::class);
    expect($subject->url())->toEqual('url');
    expect($subject->token())->toEqual('token');
    expect($subject->secret())->toEqual('secret');
});

/**
 * callback(string $token, string $secret, string $oauth_verifier): TwitterCallback
 */
it('throws an Exception if TwitterOAuth fails to retrieve a token from the callback', function () {
    // Mock the TwitterOAuth Library and assert it's usage
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)->expect(
            oauth: fn () => [],
            setOauthToken: fn () => [],
            getLastHttpCode: fn () => 401,
        )
    );

    // Create an account, assign the Twitter Provider
    // and get an AccountRedirectInterface from it
    Account::factory()
        ->withProvider(TwitterAccount::class)
        ->create()
        ->getProvider()
        ->callback('token', 'secret', 'verifier');
})->throws(
    Exception::class,
    'Unable to obtain oauth token for Twitter account, please try again',
    401
);

it('processes a succesful oAuth response, and the expected profile as a TwitterCallback', function () {
    // Mock the TwitterOAuth Library and assert it's usage
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)
            ->shouldReceive('setOauthToken')
            ->with('token', 'secret')
            ->shouldReceive('oauth')
            ->with('oauth/access_token', [
                "oauth_verifier" => 'verifier'
            ])
            ->andReturn([
                'screen_name' => 'screen_name',
                'oauth_token' => 'token',
                'oauth_token_secret' => 'secret',
            ])
            ->shouldReceive('getLastHttpCode')
            ->andReturn(200)
            ->getMock()
    );

    // Create an account, and assign the Twitter Provider to it.
    // Request a AccountRedirectInterface from it
    $subject = Account::factory()
        ->withProvider(TwitterAccount::class)
        ->create()
        ->getProvider()
        ->callback('token', 'secret', 'verifier');

    expect($subject)->toBeInstanceOf(TwitterCallback::class);
    expect($subject->profileName())->toEqual('screen_name');
    expect($subject->token())->toEqual('token');
    expect($subject->secret())->toEqual('secret');
    expect($subject->refresh())->toEqual('');
    expect($subject->toArray())->toBeArray()->toEqual([
        'profile_name' => 'screen_name',
        'token' => 'token',
        'secret' => 'secret',
        'refresh' => '',
    ]);
});

/**
 * validatePost(AccountPostInterface $post): bool
 */
it('should return false when validatePost() given invalid content', function (string $content) {
    $post = Post::factory()
        ->withAccount(TwitterAccount::class)
        ->state(compact('content'))
        ->create();

    $subject = $post->account->getProvider()->validatePost($post);

    expect($subject)->toBeFalse();
})->with('tweets.invalid');

it('should return true when validatePost() given valid content', function (string $content) {
    $post = Post::factory()
        ->withAccount(TwitterAccount::class)
        ->state(compact('content'))
        ->create();

    $subject = $post->account->getProvider()->validatePost($post);

    expect($subject)->toBeTrue();
})->with('tweets.valid');

/**
 * publishPost(AccountPostInterface $post): TwitterResponse
 */
it('should throw an error if calling publishPost() for an account that does not have a token', function () {
    $post = Post::factory()
        ->withAccount(
            type: TwitterAccount::class,
            token: ''
        )
        ->create();

    $post->account->getProvider()->publishPost($post);
})->throws(
    Exception::class,
    "The account doesn't have a token or a secret"
);

it('should throw an error if calling publishPost() for an account that does not have a secret', function () {
    $post = Post::factory()
        ->withAccount(
            type: TwitterAccount::class,
            secret: ''
        )
        ->create();

    $post->account->getProvider()->publishPost($post);
})->throws(
    Exception::class,
    "The account doesn't have a token or a secret"
);

it('should throw an error if calling publishPost() for an account that does not have an ENABLED status', function (string $status) {
    $post = Post::factory()
        ->withAccount(
            type: TwitterAccount::class,
            status: $status
        )
        ->create();

    $post->account->getProvider()->publishPost($post);
})->with([
    'PENDING',
    'DISABLED',
    'FAILED',
])->throws(
    Exception::class,
    'The account is not enabled'
);

it('should validate a post as valid before publishing it', function (string $content) {
    // Create a post with an account
    $post = Post::factory()
        ->withAccount(TwitterAccount::class)
        ->state(compact('content'))
        ->create();
    $twitter_post_id = 1550464478784921606;

    // Mock the TwitterOauth & Twitter Parser libraries and assert their use
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)
            ->shouldReceive('setOauthToken')
            ->with('token', 'secret')
            ->andReturn(null)
            ->shouldReceive('post')
            ->with('statuses/update', [
                'status'  => $post->getContent(),
            ])
            ->andReturn((object) [
                "id" => $twitter_post_id,
                "id_str" => (string) $twitter_post_id,
            ])
            ->getMock()
    );

    $this->app->instance(
        Parser::class,
        mock(Parser::class)
            ->shouldReceive('validatePost')
            ->with($post)
            ->andReturn(true)
    );

    $subject = $post->account->getProvider()->publishPost($post);
})->with('tweets.valid');

it('should validate a post as invalid and throw an exception', function (string $content) {
    // Create a post with an account
    $post = Post::factory()
        ->withAccount(TwitterAccount::class)
        ->state(compact('content'))
        ->create();

    $post->account->getProvider()->publishPost($post);
})
    ->with('tweets.invalid')
    ->throws(
        Exception::class,
        'The provided post is not a valid as a Twitter post'
    );

it('should return TwitterResponse with success if Twitter provides them', function (string $content) {
    // Create a post with an account
    $post = Post::factory()
        ->withAccount(TwitterAccount::class)
        ->state(compact('content'))
        ->create();

    $twitter_post_id = 1550464478784921606;
    $response = (object) [
        "id" => $twitter_post_id,
        "id_str" => (string) $twitter_post_id,
    ];

    // Mock the TwitterOauth & Twitter Parser libraries and assert their use
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)
            ->shouldReceive('setOauthToken')
            ->with('token', 'secret')
            ->andReturn(null)
            ->shouldReceive('post')
            ->with('statuses/update', [
                'status'  => $post->getContent(),
            ])
            ->andReturn($response)
            ->getMock()
    );

    $this->app->instance(
        Parser::class,
        mock(Parser::class)
            ->shouldReceive('validatePost')
            ->with($post)
            ->andReturn(true)
    );

    $subject = $post->account->getProvider()->publishPost($post);

    expect($subject)->toBeInstanceOf(TwitterResponse::class);
    expect($subject->id())->toBeString()->toEqual((string) $twitter_post_id);
    expect($subject->sent())->toBeBool()->toBeTrue();
    expect($subject->error())->toBeNull();
    expect($subject->raw())->toBeObject()->toEqual($response);
})->with('tweets.valid');


it('should return TwitterResponse with errors if Twitter provides them', function (string $content) {
    // Create a post with an account
    $post = Post::factory()
        ->withAccount(TwitterAccount::class)
        ->state(compact('content'))
        ->create();

    $response = (object) [
        "errors" => [
            (object) [
                'message' => 'An error',
            ]
        ]
    ];

    // Mock the TwitterOauth & Twitter Parser libraries and assert their use
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)
            ->shouldReceive('setOauthToken')
            ->with('token', 'secret')
            ->andReturn(null)
            ->shouldReceive('post')
            ->with('statuses/update', [
                'status'  => $post->getContent(),
            ])
            ->andReturn($response)
            ->getMock()
    );

    $this->app->instance(
        Parser::class,
        mock(Parser::class)
            ->shouldReceive('validatePost')
            ->with($post)
            ->andReturn(true)
    );

    $subject = $post->account->getProvider()->publishPost($post);

    expect($subject)->toBeInstanceOf(TwitterResponse::class);
    expect($subject->id())->toBeNull();
    expect($subject->sent())->toBeBool()->toBeFalse();
    expect($subject->error())->toEqual('An error');
    expect($subject->raw())->toBeObject()->toEqual($response);
})->with('tweets.valid');

/**
 * getDestinations(string $keyword, int $count = 5): array
 */
it('should throw an error if calling getDestinations for an account that does not have a token', function () {
    $post = Post::factory()
        ->withAccount(
            type: TwitterAccount::class,
            token: '',
        )
        ->create();

    $post->account->getProvider()->getDestinations('keyword');
})->throws(
    Exception::class,
    "The account doesn't have a token or a secret"
);

it('should throw an error if calling getDestinations for an account that does not have a secret', function () {
    $post = Post::factory()
        ->withAccount(
            type: TwitterAccount::class,
            secret: '',
        )
        ->create();

    $post->account->getProvider()->getDestinations('keyword');
})->throws(
    Exception::class,
    "The account doesn't have a token or a secret"
);

it('should request and process destinations from Twitter', function () {
    // Mock the TwitterOAuth Library and assert it's usage
    $this->app->instance(
        TwitterOAuth::class,
        mock(TwitterOAuth::class)
            ->shouldReceive('setOauthToken')
            ->with('token', 'secret')
            ->andReturn(null)
            ->shouldReceive('get')
            ->with('users/search', [
                'q' => 'keyword',
                'count' => 10,
            ])
            ->andReturn([
                (object) ['screen_name' => 'screen_name', 'name' => 'name'],
            ])
            ->getMock()
    );

    $post = Post::factory()
        ->withAccount(TwitterAccount::class)
        ->create();

    $subject = $post->account->getProvider()->getDestinations('keyword', 10);

    expect($subject)->toBeArray()->toHaveCount(1)->toEqual([
        'screen_name' => 'name [screen_name]',
    ]);
});
