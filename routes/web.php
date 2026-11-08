<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    sleep(3);

    Log::info("This is the web route");

    return view('welcome');
});
