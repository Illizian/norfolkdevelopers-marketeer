<?php

namespace App\Nova;
use \App\Models\Event;
use \App\Models\TemplateNotification as Model;

use \Illizian\NovaCarbonModifier\NovaCarbonModifier;
use \Illizian\NovaSuggestWrapper\NovaSuggestWrapper;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\{BelongsTo, ID, Select, Text, Textarea};
use Laravel\Nova\Http\Requests\NovaRequest;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Twitter\TwitterChannel;

class TemplateNotification extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Model::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $popularModifiers = Model::all()
            ->groupBy('modifier')
            ->map(fn($m) => $m->count())
            ->sortDesc()
            ->take(5)
            ->keys();

        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make(__('Name'), 'name')
                ->required(),

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

            NovaSuggestWrapper::make([

                Textarea::make(__('Message'), 'message')
                    ->help('The message you wish to send. Type : to add values from event, e.g. :title')
                    ->required()

            ])->suggestions(Event::$template_attributes),

            NovaCarbonModifier::make(__('Modifier'), 'modifier')
                ->popular($popularModifiers)
                ->required(),

            BelongsTo::make(__('Template'), 'template'),
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
        return [];
    }
}
