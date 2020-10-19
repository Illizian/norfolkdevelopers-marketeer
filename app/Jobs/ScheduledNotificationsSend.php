<?php

namespace App\Jobs;

use App\Models\ScheduledNotification as Model;
use App\Notifications\ScheduledNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class ScheduledNotificationsSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Model::unsent()
            ->due()
            ->get()
            ->each(function ($notification) {
                $notification->notify(new ScheduledNotification);
                $notification->sent = true;
                $notification->save();
            });

        return 0;
    }
}
