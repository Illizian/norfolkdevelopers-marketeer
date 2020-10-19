<?php

namespace App\Models;

use \App\Models\Template;
use \App\Models\Event;

use \Carbon\Carbon;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Log;

class TemplateNotification extends Model
{
    use HasFactory;

    /**
     * Takes a $date, and applies the Carbon modifiers defined on this model
     *
     * @return array
     */
    public function applyModifier(Carbon $date) : Carbon
    {
        $modifiers = collect(explode(',', $this->modifier));

        return $modifiers->reduce(function (Carbon $date, $modifier) {
            [ $method, $param ] = explode(':', $modifier) + [ null, null ];

            try {
                if ($param !== null) {
                    return $date->{$method}($param);
                } else {
                    return $date->{$method}();
                }
            } catch(Exception $e) {
                return $date;
            }

        }, $date);
    }

    /**
     * Create an array representation of this template
     * as a ScheduledNotification
     *
     * @return array
     */
    public function forEvent(Event $event) : array
    {
        return [
            'type' => $this->type,
            'destination' => $this->destination,
            'message' => $this->message,
            'scheduled_at' => $this->applyModifier($event->start_time),
        ];
    }

    /**
     * Get the template associated with this TemplateNotification
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
