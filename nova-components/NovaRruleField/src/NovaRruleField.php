<?php

namespace Illizian\NovaRruleField;

use Laravel\Nova\Fields\Field;
use \Illuminate\Support\Str;

class NovaRruleField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'NovaRruleField';

    /**
     * The meta data for the element.
     *
     * @var array
     */
    public $meta = [
        'minFrequency' => 'SECONDLY',
        'hideDates' => false,
        'hideWeekStarts' => false,
        'hideWhichDays' => false,
        'hideWhichMonths' => false,
        'hideTimes' => false,
        // The following fields should be enabled with caution, the cause significant issues for RRule libraries
        'hideOccurence' => true,
        'hideYearDay' => true,
        'hideMonthDay' => true,
        'hideWeekNo' => true,
    ];

    public static $minFrequencyOptions = [
        'SECONDLY',
        'MINUTELY',
        'HOURLY',
        'DAILY',
        'WEEKLY',
        'MONTHLY',
        'YEARLY',
    ];

    /**
     * Enables a simpler version of the field, with limited field options
     *
     * @var array
     */
    public function simple()
    {
        return $this->withMeta([
            'minFrequency' => 'DAILY',
            'hideWeekStarts' => true,
            'hideWhichMonths' => true,
            'hideOccurence' => true,
            'hideTimes' => false,
            'showHidden' => true,
        ]);
    }

    /**
     * Set the minimum frequency to appear on the list
     *
     * @param $value string
     */
    public function setMinFrequency($value)
    {
        if (!in_array(Str::upper($value), self::minFrequencyOptions)) {
            throw new Exception('Unsupported frequency, available options: ' . implode(self::minFrequencyOptions));
        }

        return $this->withMeta([ 'minFrequency' => Str::upper($value) ]);
    }

    /**
     * Hides "Start Date" & "End Date" fields
     */
    public function hideDates()
    {
        return $this->withMeta([ 'hideDates' => true ]);
    }

    /**
     * Hides "Week Starts" field
     */
    public function hideWeekStarts()
    {
        return $this->withMeta([ 'hideWeekStarts' => true ]);
    }

    /**
     * Hides "Which Days" field
     */
    public function hideWhichDays()
    {
        return $this->withMeta([ 'hideWhichDays' => true ]);
    }

    /**
     * Hides "Which Months" field
     */
    public function hideWhichMonths()
    {
        return $this->withMeta([ 'hideWhichMonths' => true ]);
    }

    /**
     * Hides "Hour", "Minute", & "Second" fields
     */
    public function hideTimes()
    {
        return $this->withMeta([ 'hideTimes' => true ]);
    }

    /**
     * Allows the user to toggle the field
     */
    public function showHidden()
    {
        return $this->withMeta([ 'showHidden' => true ]);
    }
}
