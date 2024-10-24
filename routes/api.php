<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\MobileProfileController;
use App\Http\Controllers\MobileVizController;
use Illuminate\Http\Request;
use App\Http\Controllers\MobileUploaderController;
use App\Http\Controllers\MobileHomeController;
use App\Http\Controllers\ModernlandIntegrationController;
use App\Http\Controllers\QRLoginController;
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
Route::get('/course/{lesson}/section/{section}', 'MobileLmsViewerController@seeSection');
Route::get('/course/{lesson}/sections', 'MobileLmsViewerController@seeClassSections');

Route::get('/backup-database', [DatabaseBackupController::class, 'backup']);

Route::any('/check-qrcode-authentication', [QRLoginController::class, 'checkQRCodeAuthentication'])->name('check.qrcode.authentication');
Route::post('/generate-qr-code', [QRLoginController::class, 'generateQRCode'])->name('generate.qrcode');
Route::post('/qr-login', [QRLoginController::class, 'processQRCodeLogin'])->name('qr.login');

Route::get('mobile/course/{lesson}/section/{section}', 'MobileSeeCourseController@seeSection');
Route::get('/lms/users', [ModernlandIntegrationController::class, 'getLearningUsers']);
Route::get('/lms/user/{username}/check', [ModernlandIntegrationController::class, 'isUserExist']);
Route::post('/lms/user/create', [ModernlandIntegrationController::class, 'createOrUpdateLMSUser']);
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


Route::get("/partner/login", [MitraController::class, 'login']);



Route::get("/class-list", [MobileHomeController::class, 'classList']);
Route::get("/class-categories", [MobileHomeController::class, 'classCategories']);
Route::get("/class/{id}/detail", [MobileHomeController::class, 'classDetail']);


Route::get("/viz/ais", [MobileVizController::class, 'ais']);
Route::get("/viz/quiz", [MobileVizController::class, 'getQuizResult']);
Route::get("/viz/completed-class", [MobileVizController::class, 'getCompletedClass']);
Route::get("/viz/incompleted-class", [MobileVizController::class, 'getCompletedClass']);
Route::get("/viz/section-count", [MobileVizController::class, 'sectionCount']);
Route::get("/viz/enrolled", [MobileVizController::class, 'getEnrolledClass']);
Route::get("/viz/main-chart", [MobileVizController::class, 'mainChart']);


Route::post("/store-absensi", [AbsensiController::class, 'insertAbsensiMobile']);
