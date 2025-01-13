<?php

use App\Http\Controllers\api\apiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [apiController::class,'register']);
Route::post('/login', [apiController::class,'login']);

Route::get('/alluser',[apiController::class,'allUser'])->middleware('auth:sanctum');
Route::post('/store/todo',[apiController::class,'Store'])->middleware('auth:sanctum');
Route::post('/store/subtodo',[apiController::class,'subStore'])->middleware('auth:sanctum');


// EDITE TO DO

route::put('/editetodo/{id}',[apiController::class,'editeTodo'])->middleware('auth:sanctum');