<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTransferNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payee;

    protected $after_commit = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $payee)
    {
        $this->payee = $payee;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(NotificationService $notification_service)
    {
        $status = $notification_service->notify_payee($this->payee);
        if(!$status)
            $this->release(120);
    }
}
