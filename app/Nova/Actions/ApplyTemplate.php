<?php

namespace App\Nova\Actions;

use App\Models\Template;
use App\Models\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\{ActionFields, Select};

class ApplyTemplate extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $events)
    {
        $notifications = Template::find($fields->template)->notifications;

        $events->each(function (Event $event) use ($notifications) {
            $event->notifications()->createMany(
                $notifications
                    ->map(fn($notification) => $notification->forEvent($event))
                    ->toArray()
            );
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $options = Template::all()->pluck('name', 'id')->toArray();

        return [
            Select::make('Template')->options($options),
        ];
    }
}
