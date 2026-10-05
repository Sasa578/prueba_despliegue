<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Sistema de Agenda de Pagos (práctica de despliegue)
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('servicios', ServiceController::class);
Route::resource('personas', PersonController::class);
Route::resource('pagos', PaymentController::class);
