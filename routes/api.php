<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\API\AuthController;
Use App\Http\Controllers\API\TaskController;
Use App\Http\Controllers\API\Admin\AdminController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::controller(TaskController::class)->group(function(){

    Route::post('/create-task', 'createTask');
    Route::get('/task-list', 'taskList');
    Route::post('/update-task', 'updateTask');
    Route::post('/delete-task', 'deleteTask');
    Route::post('/get-task', 'getTask');
    });
});
Route::group(['prefix' => 'admin'], function () {
Route::middleware('auth:sanctum','admin')->group(function () {
    Route::controller(AdminController::class)->group(function(){

        Route::get('/task-list', 'taskList');
        Route::post('/create-task', 'createTask');
        Route::post('/update-task', 'updateTask');
        Route::post('/assign-task', 'assignTask');
        Route::post('/delete-task', 'deleteTask');
        Route::post('/get-task', 'getTask');
        Route::get('/user-list', 'userList');
        Route::post('/get-user', 'getUser');

        
        }); 
    
});

});

Route::group([
    'middleware' => 'APIaccesstoken',
], function () {
Route::controller(AuthController::class)->group(function(){
Route::post('/login','login');
Route::post('/register','register');
});
});

