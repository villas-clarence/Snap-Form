<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicFormController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('home');
})->name('home');

Route::get('/f/{slug}', [PublicFormController::class, 'show'])->name('public.show');
Route::post('/f/{slug}/verify-email', [PublicFormController::class, 'sendVerification'])
    ->middleware('throttle:5,1')
    ->name('public.send-verification');
Route::get('/f/{slug}/verify/{token}', [PublicFormController::class, 'verifyEmail'])
    ->middleware('throttle:30,1')
    ->name('public.verify');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [FormController::class, 'index'])->name('dashboard');
    Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form}', [FormController::class, 'show'])->name('forms.show');
    Route::get('/forms/{form}/edit', [FormController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{form}', [FormController::class, 'update'])->name('forms.update');
    Route::post('/forms/{form}/archive', [FormController::class, 'archive'])->name('forms.archive');
    Route::get('/forms/{form}/results', [FormController::class, 'results'])->name('forms.results');
    Route::get('/forms/{form}/results/export', [FormController::class, 'export'])->name('forms.export');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
