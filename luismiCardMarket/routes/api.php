<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SaleController;

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

Route::prefix('users')->group(function() {
    Route::post('/create',[UserController::class,"createUser"]);
    Route::get('/login',[UserController::class,"loginUser"]);
    Route::post('/reset',[UserController::class,"resetPassword"]);
});


Route::prefix('collections')->group(function() {
    Route::post('/create',[CollectionController::class,"cardCollectionStore"])->middleware('rol');
});
Route::prefix('sales')->group(function() {
    Route::get('/search',[SaleController::class,"searchCard"]);
    Route::post('/create',[SaleController::class,"createSale"]);
});
