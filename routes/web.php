<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FgController;
use App\Http\Controllers\WipController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MaterialMasterController;
use App\Http\Controllers\PartNumberMasterController;

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
    return view('layouts.fg-dashboard');
});

Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');

Route::prefix('/dashboard')->group(function () {

    Route::get('/fg-dashboard', [FgController::class, 'index'])->name('fg.dashboard');
    Route::get('/material-dashboard', [MaterialController::class, 'index'])->name('material.dashboard');
    Route::get('/wip-dashboard', [WipController::class, 'index'])->name('wip.dashboard');

});

Route::prefix('/master')->group(function () {

    Route::get('/material-master', [MaterialMasterController::class, 'index'])->name('material.master');
    Route::get('/part-number-master', [PartNumberMasterController::class, 'index'])->name('part-number.master');

});
