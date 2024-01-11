<?php
use App\Http\Controllers\auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group( [ 'prefix' => 'auth', 'controller' => AuthController::class ],
    function() {
        Route::post( "/login", 'login' );
        Route::get( "/verify-login", 'verifyLogin' );
    },
);
Route::middleware( [ 'jwt' ] )->group( function() {
    Route::get( "auth/is-login", [ AuthController::class, 'isLogin' ] );
} );
