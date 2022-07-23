<?php

namespace App\Accounts\Contracts;

interface AccountPostInterface
{
    /**
     * Get the an optional destination for this Post
     *
     * @return string|null
     */
    public function getDestination(): ?string;

    /**
     * Get the content prepared for this Account
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * @return array<string>
     */
    public function getMedia(): array;
}
