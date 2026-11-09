<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logger('User logged in: ' . $this->user->email);
        Mail::raw('you are logged in!', function ($message) {
            $message->to($this->user->email)
                ->subject('Welcome ' . $this->user->name . ' to web site');
        });
    }
}
