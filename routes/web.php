<?php

declare(strict_types=1);

use App\Http\Controllers\ProductController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', ProductController::class)->name('home');

// Redirect dashboard to home
Route::get('dashboard', fn (): RedirectResponse => to_route('home'))->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
