<?php

use Illuminate\Http\Request;
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


Route::post('/upload', 'MobileUploaderController@upload');
Route::any('/check', 'MobileUploaderController@check');
Route::any('/close-ticket', 'MobileUploaderController@closeTicket');


Route::prefix("integration")->group(function (){
    Route::get("/check-user-exist/{userId}","ModernlandIntegrationController@checkId");
    Route::get("/user/id","ModernlandIntegrationController@getUserById");
    Route::get("/available-users","ModernlandIntegrationController@getAvailableUsers");
    Route::get("/create-new-user","ModernlandIntegrationController@createNewUser");
});

