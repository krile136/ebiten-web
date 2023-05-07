<?php

use App\Http\Controllers\Api\AesController;
use App\Http\Controllers\Api\ScoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        logger()->info($request->ip());

        return response()->json(['userName' => $user->name, 'email' => $user->email], 200);
    });
});

Route::middleware(['apiToken'])->group(function () {
    Route::post('/score', [ScoreController::class, 'storeOrUpdate']);
});

Route::post('/authenticate', [App\Http\Controllers\Api\AuthController::class, 'authenticate']);

Route::post('/encrypt', [AesController::class, 'getEncrypt']);
Route::post('/decrypt', [AesController::class, 'getDecrypt']);
