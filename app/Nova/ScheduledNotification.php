<?php

namespace App\Nova;

use \App\Models\Event;

use \Illizian\NovaSuggestWrapper\NovaSuggestWrapper;
use \Illuminate\Http\Request;
use \Laravel\Nova\Fields\{BelongsTo, Boolean, DateTime, Select, Text, Textarea, ID};
use \Laravel\Nova\Http\Requests\NovaRequest;
use \NotificationChannels\Discord\DiscordChannel;
use \NotificationChannels\Twitter\TwitterChannel;

class ScheduledNotification extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ScheduledNotification::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Schedule";
    }

    /**
     * The default sort
     *
     * @var array
     */
    public static $orderBy = ['scheduled_at' => 'asc'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id'),

            Select::make(__('Notification Type'), 'type')
                ->options([
                    DiscordChannel::class => 'Discord',
                    TwitterChannel::class => 'Twitter',
                    'mail' => 'Email',
                ])
                ->displayUsingLabels()
                ->sortable()
                ->required(),

            Text::make(__('Destination'), 'destination')
                ->help('The channel, email, or account, this message should be delivered to.')
                ->rules('required_if:type,' . DiscordChannel::class),

            NovaSuggestWrapper::make([

                Textarea::make(__('Message'), 'message')
                    ->help('The message you wish to send. Type : to add values from event, e.g. :title')
                    ->onlyOnForms()
                    ->required()

            ])->suggestions(Event::$template_attributes),

            Textarea::make(__('Message (Hydrated)'), 'hydratedMessage')
                ->onlyOnDetail()
                ->alwaysShow(),

            DateTime::make(__('Schedule'), 'scheduled_at')
                ->sortable()
                ->required(),

            Boolean::make(__('Sent'), 'sent')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            BelongsTo::make(__('Event'), 'event')
                ->sortable()
                ->nullable(),
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
            new Actions\SendNotification,
        ];
    }
}
