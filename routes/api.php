<?php

use App\Http\Controllers\URLController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('addURL',[URLController::class,'addURL']);
Route::get('showURLs',[URLController::class,'showURLs']);
Route::get('redirectToLong',[URLController::class,'redirectToLong']);