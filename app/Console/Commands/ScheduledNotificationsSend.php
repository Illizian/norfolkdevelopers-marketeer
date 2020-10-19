<?php

namespace App\Console\Commands;

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
    protected $description = 'Dispatches the ScheduledNotificationsSend job, to collect outstanding notifications and send them';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Job::dispatch();

        return 0;
    }
}
