<?php

use Illuminate\Support\Facades\Route;

Route::get('/clima', [\App\Http\Controllers\ClimaController::class, 'mostrarClima']);
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
Route::get('/mapa', [\App\Http\Controllers\MapaController::class, 'index']);


Route::get('/', function () {
    return view('welcome');
});
