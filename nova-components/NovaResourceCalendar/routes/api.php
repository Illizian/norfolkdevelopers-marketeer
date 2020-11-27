<?php

use \Illizian\NovaResourceCalendar\Http\Controllers\EventsController;
use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::resource('events', EventsController::class)
    ->only([ 'index', 'update' ]);


// Route::get('/events', function (Request $request) {
//     $start = Carbon::parse($request->query('start'));
//     $end = Carbon::parse($request->query('end'));

//     return ScheduledNotificationCalendarEvent::collection(
//         ScheduledNotification::whereBetween('scheduled_at', [ $start, $end ])->get()
//     );
// });
