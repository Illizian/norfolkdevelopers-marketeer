<?php

namespace App\Models;

use \App\Models\ScheduledNotification;
use \App\Models\Template;
use \App\Traits\ProvidesTemplateVars;

use \Carbon\Carbon;
use \Carbon\CarbonInterface;
use \Carbon\CarbonInterval;
use \Carbon\CarbonTimeZone;
use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;
use \RRule\RRule;

class Event extends Model
{
    use HasFactory;
    use ProvidesTemplateVars;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
    ];

    /**
     * The custom attributes that should be appended
     *
     * @var array
     */
    protected $appends = [
        'start_time_human', 'start_time_date', 'start_time_time', 'start_time_diff', 'duration_human', "timezone_abbr",
        "timezone_offset"
    ];

    /**
     * The attributes that should be available from template Vars.
     *
     * @var array
     */
    static $template_attributes = [
        "title", "start_time", "duration", "description", "rsvp_link", "created_at", "updated_at", "start_time_human",
        "start_time_date", "start_time_time", "start_time_diff", "duration_human", "timezone", "timezone_abbr",
        "timezone_offset"
    ];

    /**
     * Retrieve a human friendly version of the start_time e.g. Thu, 22 Jun, 7:30pm
     *
     * @return string
     */
    public function getStartTimeHumanAttribute() : string
    {
        return $this->start_time
            ->tz($this->timezone)
            ->format('D, j M, g:ia');
    }

    /**
     * Retrieve the date of start_time e.g. Thu, 22 Jun
     *
     * @return string
     */
    public function getStartTimeDateAttribute() : string
    {
        return $this->start_time
            ->tz($this->timezone)
            ->format('D, j M');
    }

    /**
     * Retrieve the time of start_time e.g. 7:30pm
     *
     * @return string
     */
    public function getStartTimeTimeAttribute() : string
    {
        return $this->start_time
            ->tz($this->timezone)
            ->format('g:ia');
    }

    /**
     * Retrieve the human friendly relative time interval e.g. 2 weeks from now
     *
     * @return string
     */
    public function getStartTimeDiffAttribute() : string
    {
        return $this->start_time->diffForHumans([
            'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
            'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS,
        ]);
    }

    /**
     * Retrieve a human friendly version of the duration e.g 2 hours
     *
     * @return string
     */
    public function getDurationHumanAttribute() : string
    {
        return CarbonInterval::make($this->duration, 'seconds')
            ->cascade()
            ->forHumans();
    }

    /**
     * Retrieve a human friendly version of the timezone e.g UTC+1
     *
     * @return string
     */
    public function getTimezoneAbbrAttribute() : string
    {
        $abbr = CarbonTimeZone::create($this->timezone)->getAbbr($this->start_time);

        return Str::upper($abbr);
    }

    /**
     * Retrieve a human friendly version of the timezone e.g UTC+1
     *
     * @return string
     */
    public function getTimezoneOffsetAttribute() : string
    {
        $offset = CarbonTimeZone::create($this->timezone)->toOffsetName($this->start_time);

        // Trim the +03:00 to +3, or, +11:00 to +11, but +11:30 remains
        return "UTC" . str_replace('+0', '+', str_replace(':00', '', $offset));
    }

    /**
     * Retrieve an \RRule\RRule interface for the rrule attribute
     *
     * @return RRule
     */
    public function getRepeatingAttribute() : RRule
    {
        return new RRule($this->rrule);
    }

    /**
     * Scope a query to only include repeating events
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRepeating(Builder $query) : Builder
    {
        return $query->whereNotNull('rrule');
    }

    /**
     * Scope a query to only include events in the past
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePassed(Builder $query) : Builder
    {
        return $query->where('start_time', '<=', now());
    }

    /**
     * Get the ScheduledNotifications associated with this Event
     */
    public function notifications()
    {
        return $this->hasMany(ScheduledNotification::class);
    }

    /**
     * Get the Templates associated with this Event
     */
    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }

    /**
     * Get the Templates associated with this Event
     */
    public function applyTemplates($templates = null)
    {
        return ($templates ?? $this->templates)->each(function ($template) {
            $this->notifications()->createMany(
                $template->notifications
                    ->map(fn($notification) => $notification->forEvent($this))
                    ->toArray()
            );
        });
    }
}
