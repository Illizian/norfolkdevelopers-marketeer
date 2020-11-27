<?php

namespace App\Http\Resources;

use \App\Models\ScheduledNotification;
use \Illuminate\Support\Str;
use \Illuminate\Http\Resources\Json\JsonResource;

class ScheduledNotificationCalendarEvent extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            /**
             * String or Integer.
             * Will uniquely identify your event. Useful for getEventById.
             */
            'id' => $this->id,
            /**
             * String or Integer.
             * Events that share a groupId will be dragged and resized together automatically.
             */
            // 'groupId' => ,
            /**
             * Boolean (true or false).
             * Determines if the event is shown in the “all-day” section of the view, if applicable.
             */
            // 'allDay' => ,
            /**
             * Date String (iso8601).
             * When your event begins. If your event is explicitly allDay, hour, minutes, seconds and milliseconds will
             * be ignored.
             */
            'start' => $this->scheduled_at->format('c'),
            /**
             * Date String (iso8601).
             * When your event ends. If your event is explicitly allDay, hour, minutes, seconds and milliseconds will be
             * ignored. If omitted, your events will appear to have the default duration.
             * (See defaultAllDayEventDuration, defaultTimedEventDuration, and forceEventDuration for more info.)
             */
            // 'end' => ,
            /**
             * Array.
             * (For defining a simple recurring event). The days of the week this event repeats. An array of integers
             * representing days e.g. [0, 1] for an event that repeats on Sundays and Mondays.
             */
            // 'daysOfWeek' => [],
            /**
             * Something duration-parseable.
             * (For defining a simple recurring event). The time of day the event starts.
             */
            // 'startTime' => ,
            /**
             * Something duration-parseable.
             * (For defining a simple recurring event). The time of day the event ends.
             */
            // 'endTime' => ,
            /**
             * Something date-parseable.
             * (For defining a simple recurring event). When recurrences of the event start.
             */
            // 'startRecur' => ,
            /**
             * Something date-parseable.
             * (For defining a simple recurring event). When recurrences of the event end.
             */
            // 'endRecur' => ,
            /**
             * String.
             * The text that will appear on an event.
             */
            'title' => Str::words($this->hydrated_message, 140, '...'),
            /**
             * String.
             * A URL that will be visited when this event is clicked by the user. For more information on
             * controlling this behavior, see the eventClick callback.
             */
            // 'url' =>,
            /**
             * String.
             * A space-separated string like 'myclass1 myclass2' Determines which HTML classNames will be attached to
             * the rendered event.
             */
            // 'classNames' =>
            /**
             * Boolean (true or false).
             * Overrides the master editable option for this single event.
             */
            // 'editable' =>
            /**
             * Boolean (true or false).
             * Overrides the master eventStartEditable option for this single event.
             */
            // 'startEditable' =>
            /**
             * Boolean (true or false).
             * Overrides the master eventDurationEditable option for this single event.
             */
            // 'durationEditable' =>
            /**
             * String.
             * Allows alternate rendering of the event, like background events.
             * Can be 'auto' (the default), 'block', 'list-item', 'background', 'inverse-background', or 'none'.
             */
            // 'display' =>
            /**
             * Boolean (true or false).
             * Overrides the master eventOverlap option for this single event. If false, prevents this event from being
             * dragged/resized over other events. Also prevents other events from being dragged/resized over this event.
             */
            // 'overlap' =>
            /**
             * String.
             * An alias for specifying the backgroundColor and borderColor at the same time.
             */
            // 'color' =>
            /**
             * String.
             * Sets an event’s background color just like the calendar-wide eventBackgroundColor option.
             */
            // 'backgroundColor' =>
            /**
             * String.
             * Sets an event’s border color just like the calendar-wide eventBorderColor option.
             */
            // 'borderColor' =>
            /**
             * String.
             * Sets an event’s text color just like the calendar-wide eventTextColor option.
             */
            // 'textColor' =>
            /**
             * Object.
             * A plain object with any miscellaneous properties. It will be directly transferred to the extendedProps
             * hash in each Event Object. Often, these props are useful in event render hooks.
             */
            'extendedProps' => [
                'type' => ScheduledNotification::$typeEnumerable[$this->type],
                'destination' => $this->destination,
                'message' => $this->hydrated_message,
                'edit' => url("nova/resources/scheduled-notifications/$this->id"),
                'event_title' => optional($this->event)->title,
                'event_start_time' => optional($this->event)->start_time
            ],
        ];
    }
}
