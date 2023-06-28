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

Route::get("/user",[UserController::class,"index"]);
Route::post("/user",function(){
    return response()->json("/user post response");
});
Route::delete("/user/{id}",function($id){
    return response("delete method".$id,200);
});
Route::put("/user/{id}",function($id){
    return response("put method".$id,200);
});

Route::post("/user/create",[UserController::class,"create"]);
