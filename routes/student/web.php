<?php

use App\Http\Controllers\Student\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [PortalController::class, 'profile'])->name('profile.index');
Route::put('/profile', [PortalController::class, 'updateProfile'])->name('profile.update');
