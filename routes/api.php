<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceProviderController;
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


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-provider', [ServiceProviderController::class, 'store']);
Route::put('/provider/{id}', [ServiceProviderController::class, 'update']);
Route::get('/providers', [ServiceProviderController::class, 'index']);
Route::get('/provider/{id}', [ServiceProviderController::class, 'show']);
Route::delete('/provider/{id}', [ServiceProviderController::class, 'destroy']);

//protected routes for authenticated users
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getAuthenticatedUser']);

});



