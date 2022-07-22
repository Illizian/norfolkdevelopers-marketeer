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
     * Returns the ID of the Post on the Provider
     *
     * @return string
     */
    public function id(): ?string;

    /**
     * Errors returned when trying to send Post
     *
     * @return string|null
     */
    public function error(): ?string;

    /**
     * Returns the response from the provider
     *
     * @return object
     */
    public function raw(): object;
}
