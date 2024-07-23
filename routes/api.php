<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessHourController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post("register",[AuthController::class, 'register'] ) ;
Route::post("login",[AuthController::class, 'login'] ) ;
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'isStaff'])->group(function () {
    Route::get("business-hours",[BusinessHourController::class, 'index' ]);
    Route::post("business-hours",[BusinessHourController::class, 'update' ]);
});
Route::get("available-hours",[AppointmentController::class, 'index' ]);
Route::post("reserve",[AppointmentController::class, 'reserve' ])->middleware('auth:sanctum');;


Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::post('staff', [UserController::class, 'store']);
    Route::get('staff', [UserController::class, 'index']);
    Route::get('staff/{id}', [UserController::class, 'show']);
    Route::put('staff/{id}', [UserController::class, 'update']);
    Route::delete('staff/{id}', [UserController::class, 'destroy']);
});


