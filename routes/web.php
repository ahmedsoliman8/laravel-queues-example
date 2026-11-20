<?php

use App\Http\Controllers\ProfileController;
use App\Jobs\ImportProductsChunk;
use App\Jobs\JobBatch1;
use App\Jobs\JobBatch2;
use App\Jobs\JobBatch3;
use App\Jobs\SendWelcomeEmail;
use App\Jobs\TryAPICall;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    /* Bus::batch([
         new ImportProductsChunk(1, 100),
         new ImportProductsChunk(101, 200),
         new ImportProductsChunk(201, 300),
         new ImportProductsChunk(301, 400),
         new ImportProductsChunk(401, 500),
     ])->dispatch();


    Bus::batch([
        new JobBatch1(),
        new JobBatch2(),
        new JobBatch3(),

    ])
        ->name('log-message')
        // Runs once, right after the batch is created, before any job runs
        ->before(function (Batch $batch) {
            Log::info("Batch {$batch->id} created with {$batch->totalJobs} jobs");
        })

        // Runs after each single job completes successfully
        ->progress(function (Batch $batch) {
            Log::info("Progress: {$batch->progress()}%");
        })

        // Runs only if ALL jobs completed successfully
        ->then(function (Batch $batch) {
            Log::info("Batch {$batch->id} finished successfully");
            // e.g. notify the user, send an email, clear a cache
        })

        // Runs when the FIRST job failure is detected
        ->catch(function (Batch $batch, Throwable $e) {
            Log::error("Batch {$batch->id} failed: {$e->getMessage()}");
        })

        // Always runs when the batch finishes (success, failure, or cancelled)
        ->finally(function (Batch $batch) {
            if ($batch->cancelled()) {
                Log::warning("Batch {$batch->id} was cancelled");
            } elseif ($batch->hasFailures()) {
                Log::warning("Batch {$batch->id} finished with {$batch->failedJobs} failed jobs");
            }
        })

        ->allowFailures()
        ->dispatch();

    */


    //SendWelcomeEmail::dispatch(User::find(1));

    TryAPICall::dispatch();

    return view('welcome');
});

Route::get('/batch/{id}', function (string $id) {
    $batch = Bus::findBatch($id);

    abort_if(is_null($batch), 404, 'Batch not found');

    return $batch;
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
