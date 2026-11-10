<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Support\Facades\Mail;

#[WithoutRelations]
class SendWelcomeEmail implements ShouldQueue,ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    //public int $uniqueFor = 20;

    public function uniqueId(): string
    {
        return $this->user->id;
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
