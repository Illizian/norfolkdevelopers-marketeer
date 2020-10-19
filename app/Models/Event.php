<?php

namespace App\Models;

use \App\Models\ScheduledNotification;
use \App\Traits\ProvidesTemplateVars;

use \Carbon\Carbon;
use \Carbon\CarbonInterface;
use \Carbon\CarbonInterval;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Model;

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
        'start_time_human', 'start_time_date', 'start_time_time', 'start_time_diff', 'duration_human',
    ];

    /**
     * The attributes that should be available from template Vars.
     *
     * @var array
     */
    static $template_attributes = [
        "title", "start_time", "duration", "description", "rsvp_link", "created_at", "updated_at", "start_time_human",
        "start_time_date", "start_time_time", "start_time_diff", "duration_human",
    ];

    /**
     * Retrieve a human friendly version of the start_time e.g. Thu, 22 Jun, 7:30pm
     *
     * @return string
     */
    public function getStartTimeHumanAttribute() : string
    {
        return $this->start_time->format('D, j M, g:ia');
    }

    /**
     * Retrieve the date of start_time e.g. Thu, 22 Jun
     *
     * @return string
     */
    public function getStartTimeDateAttribute() : string
    {
        return $this->start_time->format('D, j M');
    }

    /**
     * Retrieve the time of start_time e.g. 7:30pm
     *
     * @return string
     */
    public function getStartTimeTimeAttribute() : string
    {
        return $this->start_time->format('g:ia');
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
     * Get the ScheduledNotifications associated with this Event
     */
    public function notifications()
    {
        return $this->hasMany(ScheduledNotification::class);
    }

}
