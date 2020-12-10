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
        // $fields->template contains an ID for a template, let's grab the Entity
        $templates = collect([ Template::find($fields->template) ]);

        // Apply this template to each event
        $events->each(fn($event) => $event->applyTemplates($templates));
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
