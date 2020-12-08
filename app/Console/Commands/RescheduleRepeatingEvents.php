<?php

namespace App\Console\Commands;

use App\Jobs\RescheduleRepeatingEvents as Job;

use Illuminate\Console\Command;

class RescheduleRepeatingEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:repeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches the RescheduleRepeatingEvents job, to repeat repeating events';

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
