<?php

use App\Models\Account;
use App\Models\Post;

it('should return the provider\'s token for the Account', function () {
    $subject = Account::factory()
        ->withProvider(TwitterAccount::class)
        ->state([
            'token' => 'token'
        ])
        ->make();

    expect($subject->token())->toBeString()->toEqual('token');
});

it('should return the provider\'s secret for the Account', function () {
    $subject = Account::factory()
        ->withProvider(TwitterAccount::class)
        ->state([
            'secret' => 'secret'
        ])
        ->make();

    expect($subject->secret())->toBeString()->toEqual('secret');
});

it('should return the post\'s associated with the Account', function () {
    $subject = Account::factory()
        ->withProvider(TwitterAccount::class)
        ->hasPosts(10)
        ->create();

    expect($subject->posts)->toHaveCount(10);
});

// @TODO: It should not allow a provider that doesn't support the required interface
