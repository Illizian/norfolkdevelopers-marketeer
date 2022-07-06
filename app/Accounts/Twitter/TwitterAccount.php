<?php

namespace App\Accounts\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Accounts\Contracts\AccountInterface;
use App\Accounts\Contracts\AccountModelInterface;
use App\Accounts\Contracts\AccountPostInterface;
use Exception;
use Illuminate\Support\Facades\App;
use Twitter\Text\Parser;

class TwitterAccount implements AccountInterface
{
    public function __construct(
        protected AccountModelInterface $account,
        protected TwitterOAuth $twitter
    ) {
        //
    }

    public static function name(): string
    {
        return 'Twitter';
    }

    public static function fields(): array
    {
        return [];
    }

    public function redirect(): TwitterRedirect
    {
        // Get temporary credentials
        $tokens = $this->twitter->oauth('oauth/request_token', [
            'oauth_callback' => route('accounts.callback')
        ]);

        if ($this->twitter->getLastHttpCode() !== 200) {
            throw new Exception(
                __('An error occured when creating an authorisation URL'),
                $this->twitter->getLastHttpCode()
            );
        }

        // Get the Twitter URL where the User will complete the oAuth approval
        $url = $this->twitter->url('oauth/authorize', [
            'oauth_token' => $tokens['oauth_token']
        ]);

        /** @phpstan-ignore-next-line */
        if ($this->twitter->getLastHttpCode() !== 200) {
            throw new Exception(
                __('An error occured when creating an authorisation URL'),
                $this->twitter->getLastHttpCode()
            );
        }

        return new TwitterRedirect(
            $url,
            $tokens['oauth_token'],
            $tokens['oauth_token_secret']
        );
    }

    public function callback(
        string $token,
        string $secret,
        string $oauth_verifier
    ): TwitterCallback {
        // Create an instance of our Twitter session, and apply
        // the accounts temporarily tokens
        $this->twitter->setOauthToken($token, $secret);

        // Request oauth long lived access tokens for this Profile
        $profile = $this->twitter->oauth("oauth/access_token", [
            "oauth_verifier" => $oauth_verifier
        ]);

        if ($this->twitter->getLastHttpCode() !== 200) {
            throw new Exception(
                __('Unable to obtain oauth token for :type account, please try again', [
                    'type' => $this->name(),
                ]),
                $this->twitter->getLastHttpCode()
            );
        }

        return new TwitterCallback(
            $profile['screen_name'],
            $profile['oauth_token'],
            $profile['oauth_token_secret']
        );
    }

    public function validatePost(AccountPostInterface $post): bool
    {
        // @TODO: Do we need to validate the media?
        // @TODO: Do we need to parse DM content using different rules to Tweets?
        return (new Parser())
            ->parseTweet($post->getContent())
            ->valid;
    }

    public function publishPost(AccountPostInterface $post): TwitterResponse
    {
        if (!($this->account->token() && $this->account->secret())) {
            throw new Exception(
                __("The account doesn't have a token or a secret")
            );
        }

        if (!$this->validatePost($post)) {
            throw new Exception(
                __('Error: The provided post is not a valid as a :type post', [
                    'type' => $this->name(),
                ])
            );
        }

        // Use this account's tokens
        $this->twitter->setOauthToken(
            $this->account->token(),
            $this->account->secret()
        );

        // @TODO: Media uploads
        // @TODO: Direct messages if the $post has a destination
        // @TODO: Do we need a media upload as a DM

        // Post the status update to Twitter
        $response = $this->twitter->post("statuses/update", [
            "status" => $post->getContent(),
        ]);

        // @TODO: Actually put response in this interface
        return new TwitterResponse([]);
    }

    public function getDestinations(string $keyword, int $count = 5): array
    {
        if (!($this->account->token() && $this->account->secret())) {
            throw new Exception(
                __("The account doesn't have a token or a secret")
            );
        }

        // Use this account's tokens
        $this->twitter->setOauthToken(
            $this->account->token(),
            $this->account->secret()
        );

        // Get a list of matching users from Twitter
        $destinations = $this->twitter->get('users/search', [
            'q' => $keyword,
            'count' => $count
        ]);

        // @TODO: This should return a contract that provides getters for other profile attributes
        return collect($destinations)
            ->mapWithKeys(fn ($profile) => [
                $profile->screen_name => "$profile->name [$profile->screen_name]"
            ])
            ->toArray();
    }
}
