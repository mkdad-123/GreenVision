<?php
use App\Http\Controllers\Auth\PasswordOtpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Admin\AuthAdminController;
use App\Http\Controllers\Auth\User\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Compliant\Admin\ListCompliantController;
use App\Http\Controllers\Ai\AiDiagnoseController;
use App\Http\Controllers\Farm\FarmController;
use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\CropSale\CropSaleController;
use App\Http\Controllers\FinancialRecord\FinancialRecordController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Compliant\User\AddCompliantController;
use App\Http\Controllers\Report\ReportController;



// ADMIN  ___________________________________________________________________

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthAdminController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthAdminController::class, 'login'])->name('login.post');
    });
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AuthAdminController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AuthAdminController::class, 'logout'])->name('logout');
    });
});
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Auth\Admin\AuthAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('users', [AdminController::class, 'index'])->name('users.index');
    Route::get('complaints', [ListCompliantController::class, 'index'])->name('complaints.index');
});











// USER  ________________________________________________________________________________________

Route::prefix('user/auth/password/otp')->group(function () {
    Route::post('/send',   [PasswordOtpController::class, 'send'])->name('password.otp.send');
    Route::post('/reset',  [PasswordOtpController::class, 'reset'])->name('password.otp.reset');
    Route::post('/resend', [PasswordOtpController::class, 'resend'])->name('password.otp.resend');
});


Route::middleware('guest')->group(function () {
    Route::get('/password/otp', [PasswordOtpController::class, 'showForm'])
        ->name('password.otp.form');
    Route::post('/password/otp/reset', [PasswordOtpController::class, 'resetFromBlade'])
        ->name('password.otp.reset.web');
});





Route::middleware('guest:user')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.view');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});





Route::middleware('auth:user')->group(function () {
    Route::get('/', fn () => view('home'))->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});




Route::middleware(['web','auth:user'])
    ->prefix('user/ai/cnn')
    ->group(function () {
        Route::view('/ai/diagnose', 'ai.cnn-diagnose')->name('ai.cnn.ui');
        Route::post('/diagnose', [AiDiagnoseController::class, 'diagnose_cnn'])
            ->name('ai.cnn.diagnose');
        Route::post('/diagnose/confirm', [AiDiagnoseController::class, 'confirmSymptoms'])
            ->name('ai.cnn.confirm');
    });



    Route::middleware(['web', 'auth:user'])->group(function () {
    Route::view('/user/farm/ui', 'farm.index')->name('user.farm.ui');
    Route::prefix('user/farm')->name('user.farm.')->controller(FarmController::class)->group(function () {
        Route::get('/show-all-farms', 'index')->name('index');
        Route::get('/filter-farm',   'filter')->name('filter');
        Route::post('/add-farm',     'store')->name('store');
        Route::post('/update-farm/{id}', 'update')->name('update');
        Route::delete('/delete-farm/{id}', 'destroy')->name('destroy');
    });
});




Route::middleware('auth:user')
    ->prefix('user/equipment')
    ->controller(EquipmentController::class)
    ->group(function () {
        Route::view('/ui', 'equipment.index')->name('user.equipment.ui');
        Route::post('/add-equipments', 'store')->name('equipment.store');
        Route::put('/update-equipments/{id}', 'update')->name('equipment.update');
        Route::get('/filter-equipments', 'filter')->name('equipment.filter');
        Route::get('/show-all-equipments', 'index')->name('equipment.index');
        Route::delete('/delete-equipments/{id}', 'destroy')->name('equipment.destroy');
    });



Route::middleware('auth:user')
    ->prefix('user/inventory')
    ->controller(InventoryController::class)
    ->group(function () {
        Route::view('/ui', 'inventory.index')->name('user.inventory.ui');
        Route::post('/add-inventory', 'store');
        Route::put('/update-inventory/{id}', 'update');
        Route::delete('/delete-inventory/{id}', 'delete');
        Route::get('/show-all-inventories', 'index');
        Route::get('/filter-inventory', 'filter');
    });




    Route::middleware('auth:user')
    ->prefix('user/crop-sale')
    ->controller(CropSaleController::class)
    ->group(function () {
        Route::view('/ui', 'crop-sale.index')->name('user.crop-sale.ui');
        Route::post('/add-crop-sale', 'store');
        Route::put('/update-crop-sales/{id}', 'update');
        Route::get('/filter-crop-sales', 'filter');
        Route::get('/show-all-crop-sales', 'index');
        Route::delete('/delete-crop-sales/{id}', 'delete');
    });




    Route::middleware('auth:user')
    ->prefix('user/financialrecord')
    ->controller(FinancialRecordController::class)
    ->group(function () {
        Route::view('/ui', 'financial-records.index')->name('user.financial-records.ui');
        Route::post('/add-financialrecord', 'store');
        Route::put('/update-financialrecord/{id}', 'update');
        Route::get('/filter-financialrecord', 'filter');
        Route::get('/show-all-financialrecords', 'index');
        Route::delete('/delete-financialrecord/{id}', 'destroy');
    });




    Route::middleware('auth:user')
    ->prefix('user/task')
    ->controller(TaskController::class)
    ->group(function () {
        Route::view('/ui', 'task.index')->name('user.task.ui');
        Route::post('/add-task', 'store');
        Route::put('/update-task/{id}', 'update');
        Route::get('/filter-tasks', 'filter');
        Route::get('/show-all-tasks', 'index');
        Route::delete('/delete-task/{id}', 'delete');
    });


Route::prefix('user')->middleware('auth:user')->group(function () {

    Route::view('/complaints', 'compliant.index')
        ->name('user.complaints.ui');
    Route::post('/compliant/add-compliant', [AddCompliantController::class, 'store'])
        ->name('complaints.store');
});




Route::middleware('auth:user')->group(function () {
    Route::view('/user/report', 'report.index')
        ->name('user.report.ui');
    Route::prefix('user/report')->name('user.report.')->controller(ReportController::class)->group(function () {
        Route::get('/summary', 'summary')->name('summary');
    });
});
