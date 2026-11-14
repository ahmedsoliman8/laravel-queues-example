<?php

namespace App\Jobs;

use App\Models\Profile;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateUserSetting implements ShouldQueue
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
     * @throws \Exception
     */
    public function handle(): void
    {
      //  throw  new  \Exception('error about create user setting');
        Setting::create([
            'user_id' => $this->user->id,
            'language' => 'en',
            'timezone' => 'utc',
        ]);
    }
}
