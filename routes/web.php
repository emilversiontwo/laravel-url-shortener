<?php

use App\Http\Controllers\web\ShortUrl\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/s/{alias}', ShortUrlController::class)
    ->name('shortUrl.click');

Route::get('/', function () {
    return redirect('admin', 301);
});
