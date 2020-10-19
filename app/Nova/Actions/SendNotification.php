<?php

namespace App\Nova\Actions;

use \App\Models\ScheduledNotification;
use \App\Notifications\ScheduledNotification as Notification;

use \Illuminate\Bus\Queueable;
use \Illuminate\Contracts\Queue\ShouldQueue;
use \Illuminate\Queue\InteractsWithQueue;
use \Illuminate\Support\Collection;
use \Laravel\Nova\Actions\Action;
use \Laravel\Nova\Fields\ActionFields;

class SendNotification extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $notifications)
    {
        $notifications->each(function (ScheduledNotification  $notification) {
            $notification->notify(new Notification);
            $notification->sent = true;
            $notification->save();
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
