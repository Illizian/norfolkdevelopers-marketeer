<?php

namespace Database\Factories;

use \App\Models\Event;
use \App\Models\ScheduledNotification;
use \Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledNotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScheduledNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(array_keys(ScheduledNotification::$typeEnumerable)),
            'destination' => '',
            'message' => $this->faker->words(12, 52),
            'sent' => $this->faker->boolean(),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+6 months'),
        ];
    }
}
