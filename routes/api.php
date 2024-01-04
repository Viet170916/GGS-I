<?php

use App\Http\Controllers\auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

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
Route::group(['prefix' => 'auth', 'controller' => AuthController::class],
    function () {

        Route::post("/login", 'login');
        Route::get("/verify-login", 'verifyLogin');


    }
);
Route::post('/token', [AccessTokenController::class, 'issueToken'])
    ->middleware('throttle')
    ->name('passport.token');
Route::middleware('auth:sanctum')->group(function () {
    Route::get("/api", [\App\Http\Controllers\Controller::class, ""]);
});
