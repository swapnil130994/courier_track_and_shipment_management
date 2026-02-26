<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShipmentController;


Route::post('/login',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/logout',[AuthController::class,'logout']);

    Route::middleware('role:admin')->group(function(){
        Route::post('/shipments',[ShipmentController::class,'store']);
        // Route::delete('/shipments/{id}',[ShipmentController::class,'destroy']);
    });

    // Route::get('/shipments',[ShipmentController::class,'index']);
    Route::get('/track/{awb}',[ShipmentController::class,'show']);
});
