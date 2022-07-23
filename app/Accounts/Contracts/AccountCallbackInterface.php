<?php

namespace App\Accounts\Contracts;

interface AccountCallbackInterface
{
    /**
     * The authorised Account's profile name
     *
     * @return string
     */
    public function profileName(): string;

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
     * The oAuth refresh token for this Account
     *
     * @return string
     */
    public function refresh(): string;

    /**
     * The callback as an array
     *
     * @return array{
     *  profile_name: string,
     *  token: string,
     *  secret: string,
     *  refresh: string
     * }
     */
    public function toArray(): array;
}
