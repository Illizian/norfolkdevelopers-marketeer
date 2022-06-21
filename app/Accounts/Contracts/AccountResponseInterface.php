<?php

namespace App\Accounts\Contracts;

interface AccountResponseInterface
{
    /**
     * Returns whether the Post was sent
     *
     * @return bool
     */
    public function sent(): bool;

    /**
     * Errors returned when trying to send Post
     *
     * @return array<string>
     */
    public function error(): array;
}
