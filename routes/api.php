<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BusinessHourController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post("login",[UserController::class, 'login'] ) ;

Route::get("business-hours",[BusinessHourController::class, 'index' ]);
Route::post("business-hours",[BusinessHourController::class, 'update' ]);
Route::get("available-hours",[AppointmentController::class, 'index' ]);
Route::post("reserve",[AppointmentController::class, 'reserve' ])->middleware('auth:sanctum');;


