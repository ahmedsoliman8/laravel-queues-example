<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NotifyAdmin implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        $this->delay(30);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       // logger("NotifyAdmin {$this->user->name}  are registered to!");
        Mail::raw("NotifyAdmin {$this->user->name}  are registered ", function ($message) {
            $message->to('admin@gmail.com')
                ->subject("NotifyAdmin {$this->user->name}  are registered ");
        });
    }
}
