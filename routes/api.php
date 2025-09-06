<?php
use App\Http\Controllers\Auth\PasswordOtpController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Ai\AiDiagnoseController;
use App\Http\Controllers\Auth\Admin\AuthAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\User\AuthController;
use App\Http\Controllers\Compliant\Admin\ListCompliantController;
use App\Http\Controllers\Compliant\User\AddCompliantController;
use App\Http\Controllers\Farm\FarmController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\CropSale\CropSaleController;
use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\FinancialRecord\FinancialRecordController;
use App\Http\Controllers\Report\ReportController;
use Illuminate\Http\Request;

Route::middleware('auth:user')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('user/auth/password/otp')->group(function () {
    Route::post('/send',   [PasswordOtpController::class, 'send']);
    Route::post('/reset',  [PasswordOtpController::class, 'reset']);
    Route::post('/resend', [PasswordOtpController::class, 'resend']);
});

Route::prefix('user/auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:user');
    Route::middleware('jwt.auth')->group(function () {
        Route::get('/me', 'me'); // ← يرجّع المستخدم الحالي
    });

});


Route::middleware('auth:user')->prefix('user/farm')->controller(FarmController::class)->group(function () {
    Route::post('/add-farm', 'store');
    Route::post('/update-farm/{id}', 'update');
    Route::delete('/delete-farm/{id}', 'destroy');
    Route::get('/show-all-farms', 'index');
    Route::get('/filter-farm', 'filter');

});



Route::middleware('auth:user')->prefix('user/task')->controller(TaskController::class)->group(function () {
    Route::post('/add-task', 'store');
    Route::put('/update-task/{id}', 'update');
    Route::get('/show-all-tasks', 'index');
    Route::get('/filter-task',  'filter');
    Route::delete('/delete-task/{id}',  'delete');

});



Route::middleware('auth:user')->prefix('user/inventory')->controller(InventoryController::class)->group(function () {
    Route::post('/add-inventory',  'store');
    Route::put('/edit-inventory/{id}', 'update');
    Route::get('/show-all-inventories', 'index');
    Route::get('/filter-inventory',  'filter');
    Route::delete('/delete-inventory/{id}','delete');

});



Route::middleware('auth:user')->prefix('user/crop-sale')->controller(CropSaleController::class)->group(function () {
    Route::post('/add-crop-sale', 'store');
    Route::get('/filter-crop-sales', 'filter');
    Route::put('/update-crop-sales/{id}', 'update');
    Route::get('/show-all-crop-sales', 'index');
    Route::delete('delete-crop-sales/{id}', 'delete');

});



Route::middleware('auth:user')->prefix('user/equipment')->controller(EquipmentController::class)->group(function () {
        Route::post('/add-equipments', 'store');
        Route::put('/update-equipments/{id}', 'update');
        Route::get('/filter-equipments', 'filter');
        Route::get('/show-all-equipments', 'index');
        Route::delete('/delete-equipments/{id}', 'destroy');

});


Route::middleware('auth:user')->prefix('user/financialrecord')->controller(FinancialRecordController::class)->group(function () {
        Route::post('/add-financialrecord', 'store');
        Route::put('/update-financialrecord/{id}', 'update');
        Route::get('/filter-financialrecord', 'filter');
        Route::get('/show-all-financialrecords', 'index');
        Route::delete('/delete-financialrecord/{id}', 'destroy');

});


Route::post('admin/auth/login', [AuthAdminController::class, 'login']);
Route::post('admin/auth/logout', [AuthAdminController::class, 'logout'])->middleware('auth:admin');

Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/show-all-users', 'index')->middleware('auth:admin');

});

Route::prefix('admin/compliant')->controller(ListCompliantController::class)->group(function () {
    Route::get('/show-all-compliant', 'index')->middleware('auth:admin');

});

Route::prefix('user/compliant')->controller(AddCompliantController::class)->group(function () {
    Route::post('/add-compliant', 'store')->middleware('auth:user');

});


Route::prefix('user/report')->controller(ReportController::class)->group(function () {
    Route::get('/summary', 'summary');

});


Route::middleware('auth:user')->prefix('user/ai/cnn')->controller(EquipmentController::class)->group(function () {

Route::post('/diagnose', [AiDiagnoseController::class, 'diagnose_cnn']);
Route::post('/diagnose/confirm', [AiDiagnoseController::class, 'confirmSymptoms']);

});

