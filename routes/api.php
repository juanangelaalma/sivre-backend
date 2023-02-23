<?php

use App\Http\Controllers\Api\ApiCandidateController;
use App\Http\Controllers\Api\ApiVoterController;
use App\Http\Controllers\Auth\ApiAuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['cors', 'json.response'])->group(function () {
    Route::post('register', [ApiAuthController::class, 'register']);
    Route::post('login', [ApiAuthController::class, 'login']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [ApiAuthController::class, 'logout']);

        Route::controller(ApiVoterController::class)->group(function () {
            Route::post('voter/generate', 'generate');
            Route::get('voter/list', 'list');
            Route::delete('voter/destroy', 'destroy');
        });

        Route::controller(ApiCandidateController::class)->group(function () {
            Route::post('candidates', 'create');
            Route::get('candidates', 'index');
            Route::get('candidates/{candidate:id}', 'show');
            Route::put('candidates/{candidate:id}', 'update');
            Route::delete('candidates/{candidate:id}', 'destroy');
        });
    });
});
