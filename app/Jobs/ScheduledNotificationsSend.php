<?php

namespace App\Jobs;

use App\Models\ScheduledNotification as Model;
use App\Notifications\ScheduledNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Twitter\TwitterChannel;

class ScheduledNotificationsSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Collection of \App\Models\ScheduledNotification
     *
     * @var \Illuminate\Support\Collection
     */
    protected $models;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    function __construct(Collection $models = null)
    {
        if ($models) {
            $this->models = $models;
        } else {
            $this->models = Model::unsent()->due()->get();
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->models->each(function ($notification) {
            switch ($notification->type) {
                case TwitterChannel::class:
                    $notification->response = json_encode($notification->sendAsTweet());
                    break;

                default:
                    $notification->notify(new ScheduledNotification);
                    break;
            }

            $notification->sent = true;
            $notification->save();
        });

        return 0;
    }
}
