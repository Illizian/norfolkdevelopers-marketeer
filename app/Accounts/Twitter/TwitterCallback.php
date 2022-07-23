<?php

namespace App\Accounts\Twitter;

use App\Accounts\Contracts\AccountCallbackInterface;
use App\Accounts\Traits\ProvidesAccountCallbackAttributes;

class TwitterCallback implements AccountCallbackInterface
{
    use ProvidesAccountCallbackAttributes;

    public function __construct(
        protected string $profile_name,
        protected string $token,
        protected string $secret,
    ) {
    }
}
