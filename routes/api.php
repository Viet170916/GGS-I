<?php
use App\Facades\Elasticsearch;
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
    return Elasticsearch::getSearchResult( 'FPT' );
} );
