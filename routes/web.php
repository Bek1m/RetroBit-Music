<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\GenerationController;
use App\Http\Controllers\MusicGenerationController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Credit Routes
    Route::controller(CreditController::class)->group(function () {
        Route::get('/credits', 'index')->name('credits.index');
        Route::post('/credits/add', 'addCredits')->name('credits.add');
    });

    Route::get('/generations', [GenerationController::class, 'index'])->name('generations.index');

    // Generation Routes
    Route::controller(MusicGenerationController::class)->group(function () {
        Route::post('/generate-music', [MusicGenerationController::class, 'generate'])->name('music.generate');
        Route::get('/generations/{generation}/download', [MusicGenerationController::class, 'download'])->name('generations.download');
        Route::get('/generations/{generation}/play', [MusicGenerationController::class, 'play'])->name('generations.play');
        Route::delete('/generations/{generation}', [MusicGenerationController::class, 'destroy'])->name('generations.destroy');
    });

    // Billing Routes
    Route::controller(BillingController::class)->group(function () {
        Route::get('/billing', 'index')->name('billing.index');
    });
});

require __DIR__.'/auth.php';
