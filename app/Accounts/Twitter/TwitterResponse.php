<?php

namespace App\Accounts\Twitter;

use App\Accounts\Contracts\AccountResponseInterface;

class TwitterResponse implements AccountResponseInterface
{
    /**
     * @param object $raw
     */
    public function __construct(
        protected object $raw
    ) {
    }

    public function sent(): bool
    {
        return isset($this->raw->id_str);
    }

    public function id(): ?string
    {
        return $this->sent()
            ? $this->raw->id_str
            : null;
    }

    public function error(): ?string
    {
        // @TODO: Why null coalesce not work
        // return $this->raw?->errors[0]?->message
        return isset($this->raw->errors)
            ? $this->raw->errors[0]->message
            : null;
    }

    public function raw(): object
    {
        return $this->raw;
    }
}
