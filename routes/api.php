<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::get("/users/get/{flag}",[UserController::class,"index"]);

Route::get("/user/get/{id}",[UserController::class,"show"]);

Route::delete("/user/delete/{id}",[UserController::class,"destroy"]);

Route::post("/user/create",[UserController::class,"create"]);

Route::put("/user/update/{id}",[UserController::class,"update"]);

Route::patch("/change-password/{id}",[UserController::class,"changePassword"]);

Route::post("/register",[UserController::class,"register"]);
