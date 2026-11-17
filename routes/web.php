<?php

use App\Http\Controllers\ProfileController;
use App\Jobs\ImportProductsChunk;
use App\Jobs\JobBatch1;
use App\Jobs\JobBatch2;
use App\Jobs\JobBatch3;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Bus::batch([
        new ImportProductsChunk(1, 100),
        new ImportProductsChunk(101, 200),
        new ImportProductsChunk(201, 300),
        new ImportProductsChunk(301, 400),
        new ImportProductsChunk(401, 500),
    ])->dispatch();

    return view('welcome');
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
