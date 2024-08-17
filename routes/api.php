<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\Blog;
use App\Models\Reservation;
use Illuminate\Foundation\Console\RouteCacheCommand;
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
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/index/{id?}', [UserController::class, 'index'])->name('users.index');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::get('/me', [UserController::class, 'me'])->name('user.me')->name('me');
    //  Route::post('/store', [UserController::class, 'store'])->withoutmiddleware("auth:sanctum")->name('users.store');
    // Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');
});

//Logout Route
Route::middleware('auth:sanctum')->delete('/logout', [LogoutController::class, 'logout'])->name('logout');


//uploade profile Route
Route::post('/users/uploade/profile/{id}', [UserController::class, 'uploadProfileFile']);



//ProductRoute
Route::prefix('products')->group(function () {
    Route::get('/index', [ProductController::class, 'index'])->name('products.index');
    Route::post('/store', [ProductController::class, 'store'])->name('Products.store');
    Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
    Route::post('/upload/{id}', [ProductController::class, 'uplodImage']);
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
    Route::post('/store', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/show/{id}', [BlogController::class, 'show'])->name('blogs.show');
    Route::put('/update/{id}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/delete/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::get('/restore/{id}', [BlogController::class, 'restore'])->name('blogs.restore');
    Route::post('/upload/image/{id}', [BlogController::class, 'uploadImage']);
    //Total removal from the database Route
    Route::delete('/forcedelete/{id}', [BlogController::class, 'forcedelete'])->name('blog.forceDelete');
});

//FAQRoute
Route::prefix('faqs')->group(function () {
    Route::get('/index/{id?}', [FaqController::class, 'index'])->name('faqs.index');
    Route::post('/store', [FaqController::class, 'store'])->name('faqs.store');
    Route::put('/update/{id}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/delete/{id}', [FaqController::class, 'destroy'])->name('faqs.destroy');
    Route::post('/restore/{id}', [FaqController::class, 'restore'])->name('faqs.restore');
});

//Forget_passwordRoute
Route::post('forgetpassword', [PasswordResetController::class, 'sendResetToken']);

//PasswordChangeRoute
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.update');
});

//CommentRoute
Route::prefix('comments')->group(function () {
    Route::get('/index{id?}', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/store', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/update/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/delete/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/restore/{id}', [CommentController::class, 'restore'])->name('comments.restore');
    Route::get('/show/{id}', [CommentController::class, 'show'])->name('comments.show');
});

//OrderRoute
Route::prefix('orders')->group(function () {
    Route::get('/index/{id?}', [OrderController::class, 'index'])->name('ordees.index');
    Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/update/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/show/{id}', [OrderController::class, 'show'])->name('orders.show');
});

//ReservationRoute
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reservation', [ReservationController::class, 'store']);
});

//user->show Prifile & Update Profile
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [UserController::class, 'profile']);
    Route::get('showprofile', [UserController::class, 'showprofile']);
});

//PassengerRoute
Route::prefix('passengers')->middleware('auth:sanctum')->group(function () {
    Route::get('/index', [PassengerController::class, 'index'])->name('passengers.index');
    Route::post('/store', [PassengerController::class, 'store'])->name('passengers.store');
    Route::put('/update/{passengerId}', [PassengerController::class, 'update'])->name('passengers.update');
    Route::delete('/delete/{id}', [PassengerController::class, 'destroy'])->name('passengers.delete');
});


// Routes for Discount Codes
Route::prefix('discount_code')->group(function () {
    Route::get('index', [DiscountCodeController::class, 'index']);
    Route::post('store', [DiscountCodeController::class, 'store']);
    Route::get('show/{id}', [DiscountCodeController::class, 'show']);
    Route::put('update/{id}', [DiscountCodeController::class, 'update']);
    Route::delete('delete/{id}', [DiscountCodeController::class, 'destroy']);
});

// Route for applying discount code
Route::post('apply-discount-code', [ReservationController::class, 'aaplyDiscountCode']);