<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\Controller\StatusController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// update status
Route::post('/update-status/{id}', [StatusController::class, 'update']);
