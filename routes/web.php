<?php

use App\Jobs\LogInfoJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    LogInfoJob::dispatch();

    return view('welcome');
});
