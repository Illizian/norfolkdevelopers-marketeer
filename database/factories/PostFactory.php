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
            'status' => 'SCHEDULED',
            'scheduled_for' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
        ];
    }

    /**
     * @param string $type
     * @param mixed string
     * @param string $token
     * @param string $secret
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withAccount(
        string $type,
        string|null $name = 'Test Account',
        string|null $profile_name = 'profileName',
        string|null $token = 'token',
        string|null $secret = 'secret',
        string|null $refresh = 'refresh'
    ): Factory {
        return $this->forAccount(compact(
            'name',
            'type',
            'profile_name',
            'token',
            'secret',
            'refresh',
        ));
    }

    /**
     * Indicate that the post has been sent
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
