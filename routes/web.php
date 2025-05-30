<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
})->name('home');

Route::get('/competitions', function () {
    return Inertia::render('Competitions');
})->name('competitions');

Route::get('/competitions/{slug}', function () {
    return Inertia::render('Competition');
})->name('competition');

//Route::get('/competitions/{competition}', [CompetitionController::class, 'index'])->middleware(['auth'])->name('competitions');



// dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('competitions');
Route::get('/dashboard/new', [DashboardController::class, 'create'])->middleware(['auth'])->name('competition.create');
Route::post('/dashboard/store', [DashboardController::class, 'store'])->middleware(['auth'])->name('competition.store');
Route::get('/dashboard/{slug}', [DashboardController::class, 'show'])->middleware(['auth'])->name('competition.show');
Route::post('/dashboard/update/{competition}', [DashboardController::class, 'update'])
    ->name('competition.update');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
