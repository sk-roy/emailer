<?php


use App\Http\Controllers\EmailController;

Route::post('/emails', [EmailController::class, 'sendEmail']);
Route::get('/emails', [EmailController::class, 'getEmailList']);
