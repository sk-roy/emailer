<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('send-mail', [EmailController::class, 'sendEmailTest']);