<?php

namespace App\Nova;

use \App\Models\Event;

use \Illizian\NovaSuggestField\NovaSuggestField;
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Select::make(__('Notification Type'), 'type')
                ->options([
                    DiscordChannel::class => 'Discord',
                    TwitterChannel::class => 'Twitter',
                    'mail' => 'Email',
                ])
                ->displayUsingLabels()
                ->required(),

            Text::make(__('Destination'), 'destination')
                ->help('The channel, email, or account, this message should be delivered to.')
                ->rules('required_if:type,' . DiscordChannel::class),

            NovaSuggestField::make(__('Message'), 'message')
                ->onlyOnForms()
                ->help('The message you wish to send. Type : to add values from event, e.g. :title')
                ->suggestions(Event::$template_attributes)
                ->required(),

            Textarea::make(__('Message (Hydrated)'), 'hydratedMessage')
                ->exceptOnForms()
                ->alwaysShow(),

            DateTime::make(__('Schedule'), 'scheduled_at')
                ->required(),

            Boolean::make(__('Sent'), 'sent')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            BelongsTo::make(__('Event'), 'event')
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
