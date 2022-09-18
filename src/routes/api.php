<?php

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    logger()->info($request->ip());
    return response()->json(['userName' => $user->name, 'email' => $user->email], 200);
});

Route::post('/authenticate', [App\Http\Controllers\Api\AuthController::class, 'authenticate']);
