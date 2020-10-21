<?php

namespace App\Models;

use \App\Models\Template;
use \App\Models\Event;

use \Carbon\Carbon;
use \Illizian\NovaCarbonModifier\NovaCarbonModifier;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Log;

class TemplateNotification extends Model
{
    use HasFactory;

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
            'scheduled_at' => NovaCarbonModifier::applyModifier($event->start_time, $this->modifier),
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
