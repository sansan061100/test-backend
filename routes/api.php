<?php

use App\Http\Controllers\Api\KomisiController;
use App\Http\Controllers\Api\PembayaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('komisi', [KomisiController::class, 'getKomisi']);
Route::post('pembayaran', [PembayaranController::class, 'store']);
