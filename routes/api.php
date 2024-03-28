<?php

use Illuminate\Http\Request;
use App\Http\Controllers\MobileUploaderController;
use App\Http\Controllers\MobileHomeController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/upload', [MobileUploaderController::class, 'upload']);
Route::any('/check', [MobileUploaderController::class, 'check']);
Route::any('/close-ticket', [MobileUploaderController::class, 'closeTicket']);

Route::get("/class-list", [MobileHomeController::class, 'classList']);
Route::get("/class-categories", [MobileHomeController::class, 'classCategories']);
