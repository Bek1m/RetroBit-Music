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
        Route::get('/generations', [GenerationController::class, 'index'])->name('generations.index');
        Route::get('/credits', [CreditController::class, 'index'])->name('credits.index');
        Route::post('/generate-music', [MusicGenerationController::class, 'generate'])->name('music.generate');
        Route::get('/generations/{generation}/download', [MusicGenerationController::class, 'download'])->name('generations.download');
        Route::get('/generations/{generation}/play', [MusicGenerationController::class, 'play'])->name('generations.play');
        Route::delete('/generations/{generation}', [MusicGenerationController::class, 'destroy'])->name('generations.destroy');
        Route::get('/generations/{generation}/download', [MusicGenerationController::class, 'download'])->name('generations.download');
        Route::get('/generations/{generation}/play', [MusicGenerationController::class, 'play'])->name('generations.play');
    });

    // Billing Routes
    Route::controller(BillingController::class)->group(function () {
        Route::get('/billing', 'index')->name('billing.index');
        // Add any additional billing routes here
        // Route::post('/billing/purchase', 'purchase')->name('billing.purchase');
    });
});

require __DIR__.'/auth.php';
