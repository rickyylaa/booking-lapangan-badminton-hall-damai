<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\FieldController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\MemberController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\MessageController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\NotificationController;

use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\FrontController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\DashboardController as DashboardsController;
use App\Http\Controllers\Frontend\TransactionController as TransactionsController;

/* Route */

Auth::routes();
Route::get('/api/transactions', [DashboardController::class, 'apiTransactions']);

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/booking/{slug}', [TransactionsController::class, 'bookingForm'])->name('front.bookingForm');
Route::post('/review/{slug}', [ReviewController::class, 'reviewStore'])->name('review.store');

Route::get('/schedule', [PageController::class, 'schedule'])->name('front.schedule');
Route::get('/contact', [PageController::class, 'contact'])->name('front.contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('front.contactStore');

Route::get('/login', [LoginController::class, 'login'])->name('customer.login');
Route::post('/login', [LoginController::class, 'processLogin'])->name('customer.processLogin');
Route::get('/register', [RegisterController::class, 'register'])->name('customer.register');
Route::post('/register', [RegisterController::class, 'processRegister'])->name('customer.processRegister');

Route::prefix('/')->middleware(['customer'])->group(function () {
    Route::post('/booking/{slug}', [TransactionsController::class, 'bookingStore'])->name('customer.bookingStore');

    Route::get('/confirm/{invoice}', [TransactionsController::class, 'confirmForm'])->name('customer.confirmForm');
    Route::post('/confirm/{invoice}', [TransactionsController::class, 'confirmStore'])->name('customer.confirmStore');
    Route::get('/payment/{invoice}', [TransactionsController::class, 'paymentForm'])->name('customer.paymentForm');
    Route::post('/payment/{invoice}', [TransactionsController::class, 'paymentStore'])->name('customer.paymentStore');

    Route::get('/invoice/{invoice}', [TransactionsController::class, 'bookingInfo'])->name('customer.bookingInfo');
    Route::get('/invoice/{invoice}/pdf', [TransactionsController::class, 'bookingPDF'])->name('customer.bookingPDF');

    Route::get('/dashboard', [DashboardsController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/setting', [DashboardsController::class, 'setting'])->name('customer.setting');
    Route::put('/setting', [DashboardsController::class, 'settingUpdate'])->name('customer.settingUpdate');
    Route::get('/transaction/cancel/{invoice}', [TransactionsController::class, 'cancelForm'])->name('customer.cancelForm');
    Route::post('/transaction/cancel/{invoice}', [TransactionsController::class, 'cancelStore'])->name('customer.cancelStore');

    Route::get('/logout', [LoginController::class, 'logout'])->name('customer.logout');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/message', [MessageController::class, 'index'])->name('message.index');
    Route::delete('/message{id}', [MessageController::class, 'destroy'])->name('message.destroy');

    Route::get('/event', [EventController::class, 'index'])->name('event.index');
    Route::delete('/event{id}', [EventController::class, 'destroy'])->name('event.destroy');

    Route::post('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');

    Route::resource('/field', FieldController::class)->except(['create', 'show']);
    Route::resource('/banner', BannerController::class)->except(['create', 'show']);
    Route::resource('/customer', CustomerController::class)->except(['create', 'show']);

    Route::group(['prefix' => 'member'], function() {
        Route::get('/', [MemberController::class, 'member'])->name('member.index');
        Route::post('/accept/{invoice}', [MemberController::class, 'memberAccept'])->name('member.accept');
        Route::post('/complete/{invoice}', [MemberController::class, 'memberComplete'])->name('member.complete');

        Route::post('/', [MemberController::class, 'memberStore'])->name('member.store');
        Route::get('/{id}', [MemberController::class, 'memberEdit'])->name('member.edit');
        Route::put('/{id}', [MemberController::class, 'memberUpdate'])->name('member.update');
        Route::delete('/{id}', [MemberController::class, 'memberDestroy'])->name('member.destroy');
    });

    Route::group(['prefix' => 'transaction'], function() {
        Route::get('/', [TransactionController::class, 'transaction'])->name('transaction.index');
        Route::post('/accept/{invoice}', [TransactionController::class, 'transactionAccept'])->name('transaction.accept');
        Route::post('/playing/{invoice}', [TransactionController::class, 'transactionPlaying'])->name('transaction.playing');
        Route::post('/complete/{invoice}', [TransactionController::class, 'transactionComplete'])->name('transaction.complete');
        Route::post('/cancel/{invoice}', [TransactionController::class, 'transactionCancelStore'])->name('transaction.cancelStore');

        Route::get('/cancel/{invoice}', [TransactionController::class, 'transactionCancel'])->name('transaction.cancel');
        Route::post('/', [TransactionController::class, 'transactionStore'])->name('transaction.store');
        Route::get('/{id}', [TransactionController::class, 'transactionEdit'])->name('transaction.edit');
        Route::put('/{id}', [TransactionController::class, 'transactionUpdate'])->name('transaction.update');
        Route::delete('/{id}', [TransactionController::class, 'transactionDestroy'])->name('transaction.destroy');
    });

    Route::group(['prefix' => 'report'], function() {
        Route::get('/transaction', [ReportController::class, 'transactionReport'])->name('report.transaction');
        Route::get('/transaction/pdf/{dateRange}', [ReportController::class, 'transactionReportPdf'])->name('report.transactionPDF');
    });
});

/* Route */
