<?php

namespace App\Accounts\Contracts;

interface AccountModelInterface
{
    /**
     * The oAuth token for this Account
     *
     * @return string
     */
    public function token(): string;

    /**
     * The oAuth secret for this Account
     *
     * @return string
     */
    public function secret(): string;

    /**
     * The Account provider through which we make requests
     *
     * @return AccountInterface
     */
    public function getProvider(): AccountInterface;
}
