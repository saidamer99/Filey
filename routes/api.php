<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\FileController;

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

Route::prefix('user')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('home')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [HomeController::class, 'home']);
});
Route::prefix('group')->middleware('auth:sanctum')->group(function () {
    Route::get('/owned', [GroupController::class, 'owned']);
    Route::get('/belongs', [GroupController::class, 'belongs']);
    Route::get('/details', [GroupController::class, 'details']);
    Route::post('/add-files', [GroupController::class, 'addFiles'])->middleware('group-member');
    Route::post('/delete-files', [GroupController::class, 'DeleteFiles'])->middleware(['file-owner','group-member','can-delete-file']);
    Route::post('/check-in', [FileController::class, 'checkIn'])->middleware(['group-member','can-check-file']);
    Route::post('/check-out', [FileController::class, 'checkOut'])->middleware(['group-member','can-checkout-file']);




    Route::prefix('private')->middleware('auth:sanctum')->group(function () {
        Route::resource('groups', \App\Http\Controllers\Api\Crud\GroupController::class);
        Route::post('updateGroup', [\App\Http\Controllers\Api\Crud\GroupController::class,'updateGroup']);
        Route::post('deleteGroup', [\App\Http\Controllers\Api\Crud\GroupController::class,'deleteGroup']);
        Route::middleware('group-owner')->group(function () {
            Route::get('/users', [GroupController::class, 'users']);
            Route::post('/add-users', [GroupController::class, 'addUsers']);
            Route::post('/delete-users', [GroupController::class, 'deleteUsers'])->middleware(['can-delete-user']);
        });
    });

});

Route::prefix('files')->middleware('auth:sanctum')->group(function () {
    Route::get('/details', [FileController::class, 'details']);

});

Route::prefix('report')->group(function () {
    Route::get('/create', [\App\Http\Controllers\Api\ReportController::class, 'createReport']);
});
