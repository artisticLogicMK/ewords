<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompetitionController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
})->name('home');

Route::get('/competitions', function () {
    return Inertia::render('Competitions');
})->name('competitions');

Route::get('/dashboard', [CompetitionController::class, 'index'])->middleware(['auth'])->name('competitions');
Route::get('/dashboard/new', [CompetitionController::class, 'create'])->middleware(['auth'])->name('competition.create');
Route::post('/dashboard/store', [CompetitionController::class, 'store'])->middleware(['auth'])->name('competition.store');
Route::get('/dashboard/{slug}', [CompetitionController::class, 'show'])->middleware(['auth'])->name('competition.show');
Route::post('/dashboard/update/{competition}', [CompetitionController::class, 'update'])
    ->name('competition.update');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
