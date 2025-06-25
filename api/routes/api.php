<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/code', [CommonController::class, 'code']);
Route::get('/download', [CommonController::class, 'download']);

Route::group(['prefix' => 'user_manage'], function () {
    Route::apiResources([
        'users' => UserController::class,
    ]);
});
