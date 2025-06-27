<?php

use App\Http\Controllers\api\apiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [apiController::class,'register']);
Route::post('/login', [apiController::class,'login']);

Route::get('show/all/todo',[apiController::class,'index'])->middleware('auth:sanctum');
Route::get('/single/todo/{id}',[apiController::class,'singleTodo'])->middleware('auth:sanctum');
Route::post('store/todo',[apiController::class,'Store'])->middleware('auth:sanctum');
Route::post('/store/subtodo',[apiController::class,'subStore'])->middleware('auth:sanctum');


// EDITE TO DO

route::put('update/todo/{id}',[apiController::class,'update'])->middleware('auth:sanctum');
route::delete('/deleteTodo/{id}',[apiController::class,'deleteTodo'])->middleware('auth:sanctum');