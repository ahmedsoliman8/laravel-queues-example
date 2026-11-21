<?php

namespace App\Jobs;

use App\Jobs\Middleware\SendWelcomeEmailsEnabled;
use App\Models\User;
use DateTime;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

#[WithoutRelations]
#[DeleteWhenMissingModels]
class SendWelcomeEmail implements ShouldQueue, ShouldBeUnique
{
    use Queueable, SerializesModels;

    //public $tries = 10;
   // public $maxExceptions = 3;

   // public int $backoff=60;

    /*  public  function tries() :int{
          return 3;
      }

       public function backoff()
       {

           return[10,5,6];
       }
   */

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //  $this->delay=30;
        //$this->queue = 'mailing';
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
       // throw  new \Exception('sending email failed ');
         logger("{$this->user->email} Logged In");
         sleep(5);
       /* Mail::raw('you are logged in!', function ($message) {
            $message->to($this->user->email)
                ->subject("Welcome  {$this->user->name}  to web site");
        });*/
    }

   /* public function failed(Throwable $exception): void
    {
        Log::error("SendWelcomeEmail failed for user {$this->user->id}", [
            'error' => $exception->getMessage(),
        ]);

        Mail::raw("failed job for sending email to user  {$this->user->name}", function ($message) {
            $message->to('admin@gmail.com')
                ->subject("Failed Job");
        });


    }*/

    public function middleware(): array
    {
        return [
         //   Skip::when(config('app.send_welcome_emails')===false),
          //  new RateLimited('welcome-emails')
        ];

    }

    public function retryUntil(): DateTime
    {
        return now()->plus(minutes: 1);
    }
}
