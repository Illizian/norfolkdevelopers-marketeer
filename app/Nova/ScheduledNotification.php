<?php

namespace App\Nova;

use \App\Models\Event;

use \Illizian\NovaEmojiFieldContainer\NovaEmojiFieldContainer;
use \Illizian\NovaSuggestWrapper\NovaSuggestWrapper;
use \Illuminate\Http\Request;
use \Laravel\Nova\Fields\{BelongsTo, Boolean, DateTime, Select, Text, Textarea, ID};
use \Laravel\Nova\Http\Requests\NovaRequest;
use \NotificationChannels\Discord\DiscordChannel;

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
        return "Scheduled";
    }

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group()
    {
        return "Notifications";
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
                ->options(\App\Models\ScheduledNotification::$typeEnumerable)
                ->displayUsingLabels()
                ->sortable()
                ->required(),

            Text::make(__('Destination'), 'destination')
                ->help('The channel, email, or account, this message should be delivered to.')
                ->rules('required_if:type,' . DiscordChannel::class),

            BelongsTo::make(__('In reply to'), 'reply', ScheduledNotification::class)
                ->nullable(),

            NovaSuggestWrapper::make([
                NovaEmojiFieldContainer::make([
                    Textarea::make(__('Message'), 'message')
                        ->help('The message you wish to send. Type : to add values from event, e.g. :title')
                        ->required(),
                ]),
            ])->onlyOnForms()->suggestions(Event::$template_attributes),

            Textarea::make(__('Message (Hydrated)'), 'hydratedMessage')
                ->onlyOnDetail()
                ->alwaysShow(),

            Textarea::make(__('Message'), 'truncatedHydratedMessage')
                ->onlyOnIndex()
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
        return [
            new Filters\Sent,
        ];
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
            new Actions\MarkAsUnsent,
        ];
    }
}
