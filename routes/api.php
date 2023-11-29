<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->group(function(){

    //User's accions
    Route::post('create', [UserController::class, 'create']);
    Route::post('delete', [UserController::class, 'delete']);
    Route::post('update', [UserController::class, 'update']);

    //Card
    Route::post('create-card', [CardController::class, 'createCard']);
    Route::post('delete-card', [CardController::class, 'deleteCard']);
});



Route::post('get-token', [TokenController::class, 'getToken']);