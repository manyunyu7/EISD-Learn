<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\MobileProfileController;
use App\Http\Controllers\MobileVizController;
use Illuminate\Http\Request;
use App\Http\Controllers\MobileUploaderController;
use App\Http\Controllers\MobileHomeController;
use App\Http\Controllers\ModernlandIntegrationController;
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

Route::get('/lms/users', [ModernlandIntegrationController::class, 'getLearningUsers']);
Route::post('/lms/user/create', [ModernlandIntegrationController::class, 'createNewLMSUser']);
Route::post('/lms/user/update', [ModernlandIntegrationController::class, 'updateLMSUser']);


Route::post('/upload', [MobileUploaderController::class, 'upload']);
Route::any('/check', [MobileUploaderController::class, 'check']);
Route::any('/close-ticket', [MobileUploaderController::class, 'closeTicket']);

Route::post("/update-user-photo/", [MobileProfileController::class, 'updatePhoto']);
Route::get("/user-detail/", [MobileProfileController::class, 'getUserProfile']);
Route::post("/update-user/", [MobileProfileController::class, 'updateUser']);
Route::get("/check-integration", [MobileHomeController::class, 'checkIfAccountConnected']);
Route::get("/search-unclaimed-account", [MobileHomeController::class, 'searchUnclaimedAccount']);
Route::any("/claim-account", [MobileHomeController::class, 'claimAccount']);
Route::any("/register-class", [MobileHomeController::class, 'registerClass']);
Route::any("/coba", [MobileHomeController::class, 'completedStudent']);


Route::get("/class-list", [MobileHomeController::class, 'classList']);
Route::get("/class-categories", [MobileHomeController::class, 'classCategories']);
Route::get("/class/{id}/detail", [MobileHomeController::class, 'classDetail']);


Route::get("/viz/ais", [MobileVizController::class, 'ais']);
Route::get("/viz/quiz", [MobileVizController::class, 'getQuizResult']);
Route::get("/viz/completed-class", [MobileVizController::class, 'getCompletedClass']);
Route::get("/viz/incompleted-class", [MobileVizController::class, 'getCompletedClass']);
Route::get("/viz/enrolled", [MobileVizController::class, 'getCompletedClass']);
Route::get("/viz/main-chart", [MobileVizController::class, 'mainChart']);


Route::post("/store-absensi", [AbsensiController::class, 'insertAbsensiMobile']);
