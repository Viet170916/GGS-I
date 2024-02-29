<?php
use App\Facades\Elasticsearch;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SearchController;
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
Route::middleware( [ 'jwt' ] )->group( function() {
    Route::get( "/search", [ SearchController::class, 'search' ] );
} );
Route::get( "/test", function() {
//    Log::info("aaaa");
//    exec( 'node crawler.js --url https://daihoc.fpt.edu.vn -- 20' );
    return Elasticsearch::getClient();
//    return Elasticsearch::test( 'zara' );
} );
