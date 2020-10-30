<?php

namespace App\Console\Commands;

use App\Models\ScheduledNotification;
use App\Jobs\ScheduledNotificationsSend as Job;
use Illuminate\Console\Command;

class ScheduledNotificationsSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects any outstanding ScheduledNotification models, and dispatches to the ScheduledNotificationsSend Job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ScheduledNotification::unsent()
            ->due()
            ->get()
            ->each(function ($notification) {
                Job::dispatch($notification);
            });

        return 0;
    }
}
