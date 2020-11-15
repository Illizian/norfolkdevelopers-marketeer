<?php

namespace Database\Seeders;

use \App\Models\Event;
use \App\Models\ScheduledNotification;
use \Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Event::factory()
            ->has(ScheduledNotification::factory()->count(6), 'notifications')
            ->count(10)
            ->create();
    }
}
