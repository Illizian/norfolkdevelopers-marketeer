<?php

namespace App\Accounts\Traits;

trait ProvidesAccountRedirectAttributes
{
    public function url(): string
    {
        return $this->url;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function secret(): string
    {
        return $this->secret;
    }
}
