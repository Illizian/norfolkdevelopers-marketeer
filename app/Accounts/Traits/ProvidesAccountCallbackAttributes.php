<?php

namespace App\Accounts\Traits;

trait ProvidesAccountCallbackAttributes
{
    public function profileName(): string
    {
        return $this->profile_name;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function secret(): string
    {
        return $this->secret;
    }

    public function refresh(): string
    {
        return $this->refresh ?? '';
    }

    public function toArray(): array
    {
        return [
            'profile_name' => $this->profileName(),
            'token' => $this->token(),
            'secret' => $this->secret(),
            'refresh' => $this->refresh(),
        ];
    }
}
