<?php

use App\Http\Controllers\{
    BlogController,
    CommentController,
    DiscountCodeController,
    EmergencyContactController,
    FAQController,
    ForgotPasswordController,
    LoginController,
    LogoutController,
    NewsletterController,
    PassengerController,
    PasswordChangeController,
    ProductController,
    RegisterController,
    ReportController,
    ReservationController,
    RuleController,
    SettingController,
    TicketController,
    TickettController,
    UserController
};
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

// Public Routes

// User Registration
//Route::post('/register', [RegisterController::class, 'register'])->name('user.register');


Route::post('/register', [RegisterController::class, 'sendVerificationCode']);
Route::post('/verify', [RegisterController::class, 'verifyCode']);


// User Login
Route::post('/login', [LoginController::class, 'login'])->name('user.login');

//ForgotPassword
Route::post('/password-reset', [ForgotPasswordController::class, 'sendResetCode']);
Route::post('/password-reset/verify', [ForgotPasswordController::class, 'verifyResetCode']);
Route::post('/password-reset/new-password', [ForgotPasswordController::class, 'resetPassword']);


// Newsletter
Route::post('/newsletter/store', [NewsletterController::class, 'store'])->name('newsletter.store');

// Routes requiring authentication

Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::delete('/logout', [LogoutController::class, 'logout'])->name('logout');

    // User Routes
    Route::prefix('users')->group(function () {
        Route::get('/index/{id?}', [UserController::class, 'index'])->name('users.index');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
        Route::get('/me', [UserController::class, 'me'])->name('user.me');
        Route::post('/upload/profile/{id}', [UserController::class, 'uploadProfileFile'])->name('users.uploadProfile');
    });

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/index', [ProductController::class, 'index'])->name('products.index');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
        Route::post('/upload/{id}', [ProductController::class, 'uploadImage'])->name('products.uploadImage');
    });

    // Ticket Routes
    Route::prefix('tickets')->group(function () {
        Route::get('/index', [TicketController::class, 'index'])->name('tickets.index');
        Route::post('/store', [TicketController::class, 'store'])->name('tickets.store');
        Route::put('/update/{id}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('/delete/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
        Route::post('/restore/{id}', [TicketController::class, 'restore'])->name('tickets.restore');
        Route::post('/response/{id}', [TicketController::class, 'addResponse'])->name('tickets.addResponse');
    });

    // Rule Routes
    Route::prefix('rules')->group(function () {
        Route::post('/store', [RuleController::class, 'store'])->name('rules.store');
        Route::put('/update/{id}', [RuleController::class, 'update'])->name('rules.update');
        Route::delete('/delete/{id}', [RuleController::class, 'destroy'])->name('rules.destroy');
        Route::post('/restore/{id}', [RuleController::class, 'restore'])->name('rules.restore');
        Route::get('/index/{id?}', [RuleController::class, 'index'])->name('rules.index');
    });

    // Blog Routes
    Route::prefix('blogs')->group(function () {
        Route::post('/store', [BlogController::class, 'store'])->name('blogs.store');
        Route::get('/show/{id}', [BlogController::class, 'show'])->name('blogs.show');
        Route::put('/update/{id}', [BlogController::class, 'update'])->name('blogs.update');
        Route::delete('/delete/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');
        Route::get('/restore/{id}', [BlogController::class, 'restore'])->name('blogs.restore');
        Route::post('/upload/image/{id}', [BlogController::class, 'uploadImage'])->name('blogs.uploadImage');
        Route::delete('/forcedelete/{id}', [BlogController::class, 'forcedelete'])->name('blogs.forceDelete');
    });

    // FAQ Routes
    Route::prefix('faqs')->group(function () {
        Route::post('/store', [FAQController::class, 'store'])->name('faqs.store');
        Route::put('/update/{id}', [FAQController::class, 'update'])->name('faqs.update');
        Route::delete('/delete/{id}', [FAQController::class, 'destroy'])->name('faqs.destroy');
        Route::post('/restore/{id}', [FAQController::class, 'restore'])->name('faqs.restore');
        Route::get('/index/{id?}', [FAQController::class, 'index'])->name('faqs.index');
    });

    // Comment Routes
    Route::prefix('comments')->group(function () {
        Route::post('/store', [CommentController::class, 'store'])->name('comments.store');
        Route::put('/update/{id}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/delete/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
        Route::post('/restore/{id}', [CommentController::class, 'restore'])->name('comments.restore');
        Route::get('/show/{id}', [CommentController::class, 'show'])->name('comments.show');
        Route::get('/index/{id?}', [CommentController::class, 'index'])->name('comments.index');
    });

    // Reservation Routes
    Route::prefix('reservations')->group(function () {
        Route::get('/index', [ReservationController::class, 'index'])->name('reservations.index');
        Route::post('/store', [ReservationController::class, 'store'])->name('reservations.store');
        Route::put('/update/{id}', [ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/delete/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    });

    // Passenger Routes
    Route::prefix('passengers')->group(function () {
        Route::get('/index', [PassengerController::class, 'index'])->name('passengers.index');
        Route::post('/store', [PassengerController::class, 'store'])->name('passengers.store');
        Route::put('/update/{id}', [PassengerController::class, 'update'])->name('passengers.update');
        Route::delete('/delete/{id}', [PassengerController::class, 'destroy'])->name('passengers.destroy');
    });

    // Discount Code Routes
    Route::prefix('discount_code')->group(function () {
        Route::get('/index', [DiscountCodeController::class, 'index'])->name('discount_code.index');
        Route::post('/store', [DiscountCodeController::class, 'store'])->name('discount_code.store');
        Route::put('/update/{id}', [DiscountCodeController::class, 'update'])->name('discount_code.update');
        Route::delete('/delete/{id}', [DiscountCodeController::class, 'destroy'])->name('discount_code.destroy');
        Route::post('give_discount', [DiscountCodeController::class, 'give_discount_code']);
    });

    // Emergency Contact Routes
    Route::prefix('emergencies')->group(function () {
        Route::get('/index', [EmergencyContactController::class, 'index'])->name('emergencies.index');
        Route::post('/store', [EmergencyContactController::class, 'store'])->name('emergencies.store');
        Route::put('/update/{id}', [EmergencyContactController::class, 'update'])->name('emergencies.update');
        Route::delete('/delete/{id}', [EmergencyContactController::class, 'destroy'])->name('emergencies.destroy');
    });

    // Password Change
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.update');

    // Report Routes
    Route::prefix('reports')->group(function () {
        Route::get('/show/{productId}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/index', [ReportController::class, 'allReports'])->name('reports.index');
    });

    // Ticket Routes
    Route::prefix('ticketts')->group(function () {
        Route::get('/store', [TickettController::class, 'create'])->name('ticketts.create');
        Route::get('/index', [TickettController::class, 'index'])->name('ticketts.index');
    });
});

// Routes for applying discount code
Route::post('apply-discount-code', [ReservationController::class, 'applyDiscountCode'])->middleware('auth:sanctum')->name('apply.discount.code');

// Profile Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('showprofile', [UserController::class, 'showprofile'])->name('user.showprofile');
});



// Route::get('/test-sms', function () {
//     dispatch(new \App\Jobs\SendVerificationSMS('09021744235', '12345'));
// });
