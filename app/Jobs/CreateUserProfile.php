<?php

namespace App\Jobs;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateUserProfile implements ShouldQueue
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
        Profile::create([
            'user_id' => $this->user->id,
            'bio' => 'default bio'
        ]);
        $this->prependToChain(new ValidateUserProfile($this->user));
        $this->appendToChain(new LogUser($this->user));

    }
}
