<?php

namespace Illizian\NovaResourceCalendar\Http\Controllers;

use \App\Http\Resources\ScheduledNotificationCalendarEvent;
use \App\Models\ScheduledNotification;

use \Carbon\Carbon;
use \Illuminate\Http\Request;
use \Illuminate\Routing\Controller;

class EventsController extends Controller
{
    public function index(Request $request) {
        $start = Carbon::parse($request->query('start'));
        $end = Carbon::parse($request->query('end'));

        return ScheduledNotificationCalendarEvent::collection(
            ScheduledNotification::whereBetween('scheduled_at', [ $start, $end ])->get()
        );
    }

    public function update(Request $request, ScheduledNotification $event)
    {
        $event->scheduled_at = $request->input('scheduled_at');
        $event->save();
    }
}
