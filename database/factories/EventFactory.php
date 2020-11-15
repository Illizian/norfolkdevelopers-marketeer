<?php

namespace Database\Factories;

use \App\Models\Event;
use \Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3,6),
            'start_time' => $this->faker->dateTimeBetween('now', '+6 months'),
            'duration' => $this->faker->randomElement([3600, 5400, 7200, 14400]),
            'description' => $this->faker->paragraphs($this->faker->randomDigit(), true),
            'rsvp_link' => $this->faker->url(),
            'timezone' => $this->faker->timezone,
        ];
    }
}
