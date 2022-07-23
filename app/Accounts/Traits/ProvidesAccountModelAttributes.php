<?php

namespace App\Accounts\Traits;

use App\Accounts\Contracts\AccountInterface;
use Illuminate\Support\Facades\App;

trait ProvidesAccountModelAttributes
{
    public function status(): string
    {
        return $this->status;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function secret(): string
    {
        return $this->secret;
    }

    public function getProvider(): AccountInterface
    {
        return App::make($this->type, ['account' => $this]);
    }
}
