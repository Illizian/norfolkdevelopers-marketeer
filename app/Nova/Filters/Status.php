<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class Status extends BooleanFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        // $value = [ 'pending' => true, 'sending' => false, 'sent' => false, 'failed' => true ]
        // We use array_keys(array_filter), to convert $value into [ 'pending', 'failed' ]
        return $query->whereIn('status', array_keys(array_filter($value)));
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            "Pending" => "pending",
            "Sending" => "sending",
            "Sent" => "sent",
            "Failed" => "failed",
        ];
    }

    /**
     * Set the default options for the filter.
     *
     * @return array|mixed
     */
    public function default()
    {
        return [
            "pending" => true,
            "sending" => true,
        ];
    }
}
