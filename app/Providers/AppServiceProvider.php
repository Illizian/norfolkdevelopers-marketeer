<?php

namespace App\Providers;

use \App\Notifications\ScheduledNotification;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            Log::info("Quere::before({$event->job->resolveName()})");
            if ($event->job->resolveName() === ScheduledNotification::class) {
                $notifiables = unserialize($event->job->payload()['data']['command'])->notifiables;

                $notifiables->each(function($notifiable) use ($event) {
                    Log::info("Quere::before({$event->job->resolveName()}) - Updating status of #{$notifiable->id}");
                    $notifiable->status = 'sending';
                    $notifiable->save();
                });
            }
        });

        Queue::after(function (JobProcessed $event) {
            Log::info("Quere::after({$event->job->resolveName()})");
            if ($event->job->resolveName() === ScheduledNotification::class) {
                $notifiables = unserialize($event->job->payload()['data']['command'])->notifiables;

                $notifiables->each(function($notifiable) use ($event) {
                    $status = $event->job->hasFailed() ? 'failed' : 'sent';
                    Log::info(
                        "Quere::after({$event->job->resolveName()}) - Updating status of #{$notifiable->id} to $status"
                    );
                    $notifiable->status = $status;
                    $notifiable->save();
                });
            }
        });
    }
}
