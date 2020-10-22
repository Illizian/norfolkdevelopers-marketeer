<?php

namespace App\Nova;

use \Illuminate\Http\Request;
use \Laravel\Nova\Fields\{ID, Text, DateTime, Number, Markdown, HasMany, Timezone};
use \Laravel\Nova\Http\Requests\NovaRequest;

class Event extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Event::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

    /**
     * The default sort
     *
     * @var array
     */
    public static $orderBy = ['start_time' => 'asc'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make(__('Title'), 'title')
                ->required(),

            DateTime::make(__('Date and time'), 'start_time')
                ->sortable()
                ->required(),

            Number::make(__('Duration (seconds)'), 'duration')
                ->onlyOnForms()
                ->required(),

            Text::make(__('Duration'), 'duration_human')
                ->exceptOnForms()
                ->required(),

            Timezone::make(__('Timezone'), 'timezone')
                ->searchable()
                ->default(config('app.timezone'))
                ->help('Defaults to ' . config('app.timezone'))
                ->required(),

            Markdown::make(__('Description'), 'description')
                ->required(),

            Text::make(__('RSVP Link'), 'rsvp_link'),

            HasMany::make(__('Scheduled Notifications'), 'notifications'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new Actions\ApplyTemplate,
        ];
    }
}
