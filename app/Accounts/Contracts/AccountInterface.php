<?php

namespace App\Accounts\Contracts;

use Illuminate\Http\Request;

interface AccountInterface
{
    /**
     * A Provider or Service name for the
     * type of Account this publishes posts to
     *
     * @return string
     */
    public static function name(): string;

    /**
     * The fields this account requires in
     * order to publish posts
     *
     * @return array<string, string>
     */
    public static function fields(): array;

    /**
     * Returns an interface containing the URL required to perform oAuth
     *
     * @param Request $request
     * @return AccountRedirectInterface
     */
    public function redirect(Request $request): AccountRedirectInterface;

    /**
     * Resolve the callback from the Provider
     * into an AccountAuthorizeResponseInterface
     *
     * @param Request $request
     * @return AccountCallbackInterface
     */
    public function callback(Request $request): AccountCallbackInterface;

    /**
     * For a given $post this method should validate
     * it for the given network
     *
     * @param AccountPostInterface $post
     * @return bool
     */
    public function validatePost(AccountPostInterface $post): bool;

    /**
     * For a given $post this method should publish it
     * to the provider and return a Response
     *
     * @param AccountPostInterface $post
     * @return AccountResponseInterface
     */
    public function publishPost(AccountPostInterface $post): AccountResponseInterface;

    /**
     * Get the possible destinations for this provider, it should return
     * an array of the following shape
     * [
     *   "<destination's id>" => "<destination's name>",
     *   "680882586242908176" => "#announcements",
     *   "@illizian" => "@illizian",
     * ]
     *
     * @param string $keyword
     * @param int $count
     * @return array<string, string>
     */
    public function getDestinations(string $keyword, int $count = 5): array;
}
