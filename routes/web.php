<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use Inertia\Inertia;

// Site
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/competitions', [SiteController::class, 'competitions'])->name('site.competitions');
Route::get('/latest', [SiteController::class, 'latest'])->name('site.latest');
Route::get('/pastwinners', [SiteController::class, 'pastWinners'])->name('site.pastwinners');
Route::get('/competitions/{slug}', [SiteController::class, 'competition'])->name('site.competition');
Route::get('/competitions/{slug}/join', [SiteController::class, 'join'])->name('site.join');
Route::get('/competitions/{slug}/contestants', [SiteController::class, 'contestants'])->name('site.contestants');
Route::post('/competitions/{slug}/storeContestant', [SiteController::class, 'storeContestant'])->name('site.storeContestant');
Route::get('/competitions/{competition:slug}/contestants/{contestant:slug}', [SiteController::class, 'contestant'])->name('site.contestant');
Route::post('/competitions/{competition:slug}/contestants/{contestant:slug}/vote', [SiteController::class, 'addVotes'])->name('contestant.vote');




// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('competitions');
Route::get('/dashboard/new', [DashboardController::class, 'create'])->middleware(['auth'])->name('competition.create');
Route::get('/dashboard/password', function () {
    return Inertia::render('dashboard/Password');
})->name('password');
Route::post('/dashboard/store', [DashboardController::class, 'store'])->middleware(['auth'])->name('competition.store');
Route::get('/dashboard/{slug}', [DashboardController::class, 'show'])->middleware(['auth'])->name('competition.show');
Route::post('/dashboard/update/{competition}', [DashboardController::class, 'update'])
    ->name('competition.update');
Route::delete('/dashboard/{competition}', [DashboardController::class, 'destroy'])->name('competition.destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
