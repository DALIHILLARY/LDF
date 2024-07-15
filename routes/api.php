<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




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



Route::group(['middleware' => ['auth:api']], function () {
    // $token = $request->bearerToken();
    // if (!$token) {
    //     return response()->json(['error' => 'Token not provided'], 401);
    // }

    // $user = Auth::guard('api')->user();
    // if ($user) {
    //     return response()->json(['user' => $user]);
    // } else {
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    Route::post('/logout', [AuthController::class, 'logout']);
});



