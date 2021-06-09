<?php

namespace Tests\Unit;

use App\Models\Event;
use Carbon\Carbon;
use Tests\TestCase;

class ProvidesRepeatingScheduleTest extends TestCase
{

    // 1. Event Model
    //    - With known RRule
    // 2. Check if an RRule can be exhausted
    // 3. Check if an RRule returns correct date given a known system date/time

    public function test_should_find_schedule_exhausted()
    {
        $event = Event::factory()->create([
            'start_time' => '2022-06-09 12:00:00',
            'rrule' => "DTSTART:20220609T000000Z\nRRULE:FREQ=WEEKLY;COUNT=2;INTERVAL=1"
        ]);

        // On a date before 2022-06-09, test should find schedule is not exhausted
        Carbon::setTestNow(Carbon::create(2021, 6, 9));
        $this->assertFalse($event->scheduled_exhausted);

        // On a date after 2022-06-09, test should find schedule is not exhausted
        Carbon::setTestNow(Carbon::create(2022, 6, 20));
        $this->assertTrue($event->scheduled_exhausted);
    }

    public function test_should_find_next_scheduled_dates()
    {
        $event = Event::factory()->create([
            'start_time' => '2022-06-09 12:00:00',
            'rrule' => "DTSTART:20220609T000000Z\nRRULE:FREQ=WEEKLY;COUNT=2;INTERVAL=1"
        ]);

        // On a date before 2022-06-09, test should find next date is 2022-06-09
        Carbon::setTestNow(Carbon::create(2021, 6, 9));
        $this->assertEquals(Carbon::create(2022, 6, 9, 0, 0, 0, 0), $event->scheduled_next);

        // On a date after 2022-06-09, but before 2022-06-16 test should find next date is 2022-06-16
        Carbon::setTestNow(Carbon::create(2022, 6, 12));
        $this->assertEquals(Carbon::create(2022, 6, 16, 0, 0, 0, 0), $event->scheduled_next);

        // On a date after 2022-06-16, test should find no further date
        Carbon::setTestNow(Carbon::create(2022, 6, 20));
        $this->assertNull($event->scheduled_next);
    }
}
