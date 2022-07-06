<?php

namespace App\Accounts\Twitter;

use App\Accounts\Contracts\AccountResponseInterface;

class TwitterResponse implements AccountResponseInterface
{
    /**
     * @param array<string> $raw
     */
    public function __construct(
        protected array $raw
    ) {
    }

    public function sent(): bool
    {
        // @TODO: Extract this boolean from a $raw attribute
        return false;
    }

    public function error(): array
    {
        // @TODO: Extract an array of error messages from a $raw attribute
        return [];
    }
}
