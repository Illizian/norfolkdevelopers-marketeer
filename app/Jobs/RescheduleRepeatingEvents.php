<?php

namespace App\Jobs;

use App\Models\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RescheduleRepeatingEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Event::repeating()
            ->passed()
            ->get()
            ->filter(fn($event) => $event->repeating->count() > 0)
            ->each(function($event) {
                // Set a new start_time to the next occurence of the Rrule
                $event->start_time = $event->repeating->getNthOccurrenceAfter(now(), 1);

                // Create any configured ScheduledNotifications from assigned TemplateNotifications
                $event->applyTemplates();

                $event->save();
            });

        return 0;
    }
}
