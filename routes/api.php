<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\Blog;
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

Route::group(["middleware" => "auth:sanctum"], function () {

    //User Route
    Route::prefix('users')->group(function () {
        Route::get('/index/{id}', [UserController::class, 'index'])->name('users.index');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
        Route::get('/me', [UserController::class, 'me'])->name('user.me');
    });
});

//ProductRoute
Route::prefix('products')->group(function () {
    Route::get('/index', [ProductController::class, 'index'])->name('products.index');
    Route::post('/store', [ProductController::class, 'store'])->name('Products.store');
    Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
});

//TicketRoute
Route::prefix('tickets')->group(function () {
    Route::get('/index', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/store', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/show/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::put('/update/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/delete/{id}', [TicketController::class, 'destroy'])->name('tikets.destroy');
    Route::post('/restore/{id}', [TicketController::class, 'restore'])->name('tickets.restore');
});

//RuleRoute
Route::prefix('rules')->group(function () {
    Route::get('/index/{id?}', [RuleController::class, 'index'])->name('rules.index');
    Route::post('/store', [RuleController::class, 'store'])->name('rules.store');
    Route::put('/update/{id}', [RuleController::class, 'update'])->name('rules.update');
    Route::delete('/delete/{id}', [RuleController::class, 'destroy'])->name('rules.destroy');
    Route::post('/restore/{id}', [RuleController::class, 'restore'])->name('rules.restore');
});

//BlogRoute
Route::prefix('blogs')->group(function () {
    Route::get('/index', [BlogController::class, 'index'])->name('blogs.index');
    Route::post('/blog', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/show/{id}', [BlogController::class, 'show'])->name('blogs.show');
    Route::put('/update/{id}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/delete/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::post('/restore/{id}', [BlogController::class, 'restore'])->name('blogs.restore');
    //Total removal from the database Route
    Route::delete('/forcedelete/{id}', [BlogController::class, 'forcedelete'])->name('blog.forceDelete');
});
