<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\publicController;

use App\Http\Controllers\User\dashboardController as userDashboardController;
use App\Http\Controllers\User\requestController as userRequestController;
use App\Http\Controllers\User\requestHistoryController as userRequestHistoryController;

use App\Http\Controllers\Admin\dashboardController as adminDashboardController;
use App\Http\Controllers\Admin\budgetController as adminBudgetController;
use App\Http\Controllers\Admin\requestController as adminRequestController;
use App\Http\Controllers\Admin\importController as adminImportController;
use App\Http\Controllers\Admin\userController as adminUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.login');
})->middleware('guest');


Route::middleware(['guest'])->group(function () {

    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login-auth', [LoginController::class, 'authenticate'])->name('login.auth');
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register-store', [RegisterController::class, 'store'])->name('register.store');

});
Route::get('/image/{url}', [publicController::class, 'getImage'])->where('url', '(.*)')->name('getImage');

Route::middleware(['auth'])->group(function () {


    Route::middleware(['user'])->group(function () {
        Route::get('/', [userDashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/request', [userRequestController::class, 'index'])->name('user.request');
        Route::post('/request', [userRequestController::class, 'store'])->name('user.requestStore');
        Route::post('/request/ubah-item/{index}', [userRequestController::class, 'ubahCart'])->name('user.requestUbahJumlah');
        Route::post('/request/add-item', [userRequestController::class, 'addItem'])->name('user.requestAddItem');
        Route::get('/request/history', [userRequestHistoryController::class, 'index'])->name('user.requestHistory');
        //request history detail
        Route::get('/request/history/{id}', [userRequestHistoryController::class, 'detail'])->name('user.historyDetail');
        Route::post('/request/history/{id}/delete', [userRequestHistoryController::class, 'cancel'])->name('user.requestCancel');

    });
    Route::middleware(['admin'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/request/{id}/export', [adminRequestController::class,'export'])->name('admin.requestExport');
            Route::post('/request/export', [adminRequestController::class,'exportList'])->name('admin.requestListExport');
            Route::get('/', [adminDashboardController::class, 'index'])->name('admin.dashboard');

            Route::get('/budget', [adminBudgetController::class, 'index'])->name('admin.budget');
            Route::get('/request', [adminRequestController::class, 'history'])->name('admin.requestHistory');
            Route::get('/request/{id}', [adminRequestController::class, 'detail'])->where('id', '[0-9]+')->name('admin.requestDetail');
            Route::post('/request/{id}', [adminRequestController::class, 'update'])->where('id', '[0-9]+')->name('admin.requestUpdate');
            //import item
            Route::post('/import', [adminImportController::class, 'item'])->name('admin.importItems');
            Route::post('/import/budget', [adminImportController::class, 'budget'])->name('admin.importBudget');

            //user listroute
            Route::get('/user', [adminUserController::class, 'list'])->name('admin.userList');
            Route::post('/user/{username}', [adminUserController::class, 'delete'])->name('admin.userDelete');
            Route::post('/import/user', [adminImportController::class, 'user'])->name('admin.importUser');
        });

    });

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout.auth');





});
