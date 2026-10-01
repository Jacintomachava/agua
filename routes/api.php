<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\BancoCarteiraController;
use App\Http\Controllers\FuroController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LeituraController;
use App\Http\Controllers\Auth\AuthUserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/distritos/{provinciaID}', [DistritoController::class, 'apiDistritos']);
Route::get('/bancos/carteiras/{formaPagamentoID}', [BancoCarteiraController::class, 'apiBancosCarteiras']);
Route::post('/login-app', [AuthUserController::class, 'loginApp']);

Route::middleware('auth:sanctum')->get('/meus-furos', [FuroController::class, 'meusFuros']);
Route::middleware('auth:sanctum')->post('/mudar-furo', [FuroController::class, 'mudarFuroApp']);
Route::middleware('auth:sanctum')->get('/clientes', [ClienteController::class, 'clientesFuro']);
Route::middleware('auth:sanctum')->get('/leituras-pendentes', [LeituraController::class, 'leiturasPendentesApp']);

