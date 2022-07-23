<?php

namespace App\Accounts\Contracts;

interface AccountRedirectInterface
{
    /**
     * The URL to redirect to User to for oAauth approval
     *
     * @return string
     */
    public function url(): string;

    /**
     * Our Twitter App token
     *
     * @return string
     */
    public function token(): string;

    /**
     * Our Twitter App secret
     *
     * @return string
     */
    public function secret(): string;
}
