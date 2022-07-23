<?php

namespace App\Accounts\Twitter;

use App\Accounts\Contracts\AccountRedirectInterface;
use App\Accounts\Traits\ProvidesAccountRedirectAttributes;

class TwitterRedirect implements AccountRedirectInterface
{
    use ProvidesAccountRedirectAttributes;

    public function __construct(
        protected string $url,
        protected string $token,
        protected string $secret
    ) {
    }
}
