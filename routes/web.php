<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dht22Controller;
use App\Http\Controllers\LampController;

Route::get('/', function () {
    return view('welcome');
});

// ===============================
// ROUTE SENSOR
// ===============================
Route::get('/update-data/{tmp}/{hmd}', [Dht22Controller::class, 'updateData']);
Route::get('/get-data', [Dht22Controller::class, 'getData']);
Route::post('/update-nilai-maksimal', [Dht22Controller::class, 'updateNilaiMaksimal']);

// ===============================
// ROUTE LAMPU
// ===============================
Route::get('/get-lamp-status', [LampController::class, 'getLampStatus']);
Route::get('/get-lamp/{number}', [LampController::class, 'getLamp']); // ✅ TAMBAHKAN INI!
Route::post('/control-lamp', [LampController::class, 'controlLamp']); // ✅ PERBAIKI TYPO!
Route::post('/control-all-lamps', [LampController::class, 'controlAllLamps']);