<?php

use App\Http\Controllers\web\ShortUrl\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{alias}', ShortUrlController::class)
    ->name('shortUrl.click');
