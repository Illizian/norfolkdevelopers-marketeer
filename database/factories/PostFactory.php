<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'content' => $this->faker->sentence(),
            'scheduled_for' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
        ];
    }

    /**
     * @param string $type
     * @param string name
     * @param ?string $status
     * @param ?string $profile_name
     * @param ?string $token
     * @param ?string $secret
     * @param ?string $secret
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withAccount(
        string $type,
        string $name = 'Test Account',
        ?string $status = 'ENABLED',
        ?string $profile_name = 'profileName',
        ?string $token = 'token',
        ?string $secret = 'secret',
        ?string $refresh = 'refresh'
    ): Factory {
        return $this->forAccount(compact(
            'name',
            'type',
            'status',
            'profile_name',
            'token',
            'secret',
            'refresh',
        ));
    }

    /**
     * Indicate that the post has been sent
     *
     * @param Carbon $at
     * @param array $response
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sent(Carbon $at, array $response): Factory
    {
        return $this->state(fn () => [
            'sent' => $at,
            'response' => $response,
        ]);
    }
}
