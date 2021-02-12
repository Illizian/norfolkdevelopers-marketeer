<?php

namespace App\Traits;

use \RRule\RRule;

trait ProvidesRepeatingSchedule {
    /**
     * The attribute that stores the RRule string.
     *
     * @var array
     */
    protected $RRuleAttribute = 'rrule';

    /**
     * Retrieve an \RRule\RRule interface for the $RRuleAttribute attribute
     *
     * @return RRule
     */
    public function getRepeatingAttribute() : RRule
    {
        return new RRule($this->{$this->RRuleAttribute});
    }

    /**
     * Determine if the \RRule\RRule has completed and is unable to generate more dates
     *
     * @return boolean
     */
    public function getScheduledExhaustedAttribute() : bool
    {
        return (
            $this->repeating->isFinite() &&
            count($this->repeating->getOccurrencesAfter(now())) === 0
        );
    }

    /**
     * Get the next occurence of the \RRule\RRule from `now()`
     *
     * @return boolean
     */
    public function getScheduledNextAttribute() : \DateTime
    {
        return $this->repeating->getNthOccurrenceAfter(now(), 1);
    }
}
