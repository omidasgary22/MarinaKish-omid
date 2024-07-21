<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//RegisterUser
Route::post('/register', [RegisterController::class, 'Register'])->name('user.register');

//LoginUser
Route::post('/login', [LoginController::class, 'Login'])->name('user.login');


//User Route
Route::prefix('users')->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('users.index');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
});
