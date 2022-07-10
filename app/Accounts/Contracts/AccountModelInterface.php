<?php

namespace App\Accounts\Contracts;

interface AccountModelInterface
{
    /**
     * Return the current status of the account or
     * update the status of the current account
     * when provided a new $status parameter
     *
     * <PENDING|ENABLED|DISABLED|FAILED>
     *
     * @return string
     */
    public function status(): string;

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
