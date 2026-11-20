<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TryAPICall implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('TryAPICall');
        $response = Http::get('https://httpbin.org/status/429');
        Log::info("Response Status Code : {$response->status()}");

        if ($response->status() == 429) {
            $this->release(30);
            return;

        }
        if ($response->status() != 200) {
            $this->fail();
            return;

        }
    }
}
