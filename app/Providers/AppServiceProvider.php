<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\TwilioClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TwitterOAuth::class, function ($app) {
            return new TwitterOAuth(
                config('services.twitter.consumer_key'),
                config('services.twitter.consumer_secret'),
                config('services.twitter.access_token'),
                config('services.twitter.access_secret')
            );
        });

        $this->app->bind(TwilioClient::class, function ($app) {
            return new TwilioClient(
                config('services.twilio.sid'),
                config('services.twilio.token'),
                config('services.twilio.from'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Http\Resources\ScheduledNotificationCalendarEvent::withoutWrapping();
    }
}
