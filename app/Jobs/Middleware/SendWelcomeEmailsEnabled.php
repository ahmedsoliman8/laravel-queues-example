<?php

namespace App\Jobs\Middleware;

use Closure;

class SendWelcomeEmailsEnabled
{
    /**
     * Process the queued job.
     *
     * @param  Closure(object): void  $next
     */
    public function handle(object $job, Closure $next): void
    {

        if (config('app.send_welcome_emails') === false) {

            $job->fail();
            return;

        }
        $next($job);
    }
}
