<?php

namespace App\Jobs;

use App\Models\ScheduledNotification as Model;
use App\Notifications\ScheduledNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ScheduledNotificationsSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Collection of
     *
     * @var \App\Models\ScheduledNotification
     */
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->model->notify(new ScheduledNotification);

        return 0;
    }

    public function failed(\Exception $e)
    {
        Log::error(Self::class . "::failed");

        $this->model->status = 'failed';
        $this->model->save();
    }
}
