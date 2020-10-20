<?php

namespace App\Models;

use App\Models\Event;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ScheduledNotification extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'destination',
        'message',
        'scheduled_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * Retrieve a hydrated version of the message, with
     * fields from the event interpolated
     *
     * @return string
     */
    public function getHydratedMessageAttribute() : string
    {
        if ($this->event) {
            return strtr($this->message, $this->event->template_vars);
        }

        return $this->message;
    }

    /**
     * Scope a query to only include notifications that are unsent
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnsent(Builder $query) : Builder
    {
        return $query->where('sent', 0);
    }

    /**
     * Scope a query to only include notifications that are unsent
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue(Builder $query) : Builder
    {
        return $query->where('scheduled_at', '<=', now());
    }


    /**
     * Get the event associated with this Notification
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the Twitter Credentials required to make this
     * TODO: This is a placeholder for if/when we need to support mutiple twitter accounts
     */
    // public function routeNotificationForTwitter()
    // {
    //    return [
    //       'TWITTER_CONSUMER_KEY',
    //       'TWITTER_CONSUMER_SECRET',
    //       'TWITTER_ACCESS_TOKEN',
    //       'TWITTER_ACCESS_SECRET',
    //    ];
    // }

    /**
     * Get the Discord channel this Notification should be sent to
     */
    public function routeNotificationForDiscord()
    {
        return $this->destination;
    }

    /**
     * Get the Email address this Notification should be sent to
     */
    public function routeNotificationForMail()
    {
        return $this->destination;
    }
}