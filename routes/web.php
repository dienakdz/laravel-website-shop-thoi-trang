<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\clients\HomeController;
use App\Http\Controllers\clients\ProductsController;
use App\Http\Controllers\clients\ContactController;
use App\Http\Controllers\clients\CartsController;
use App\Http\Controllers\clients\CheckoutController;
use App\Http\Controllers\clients\DetailProductController;
use App\Http\Controllers\clients\LoginController;
use App\Http\Controllers\clients\OrderedController;
use App\Http\Controllers\clients\AboutController;
use App\Http\Controllers\clients\InforController;

// Admin
use App\Http\Controllers\admin\LoginAdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\OrderAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== CÁC ROUTE CÔNG KHAI (PUBLIC) ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [ProductsController::class, 'index'])->name('product');
Route::get('/chi-tiet-san-pham/{id?}', [DetailProductController::class, 'index'])->name('detail-product');
Route::post('/danh-gia-san-pham/{id?}', [DetailProductController::class, 'postRating'])->name('rating-product');
Route::get('/tim-kiem-san-pham/{search?}', [ProductsController::class, 'search'])->name('search');

Route::get('/gioi-thieu', [AboutController::class, 'index'])->name('about');
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'postContact'])->name('post-contact');

// Xác thực tài khoản
Route::get('/tai-khoan', [LoginController::class, 'index'])->name('login');
Route::post('/postLogin', [LoginController::class, 'postLogin'])->name('postLogin');
Route::post('/tai-khoan', [LoginController::class, 'create'])->name('newUser');
Route::get('/logout', [LoginController::class, 'getLogout'])->name('logout');


// ==================== CÁC ROUTE YÊU CẦU ĐĂNG NHẬP ====================
Route::middleware('client.auth')->group(function () {
    // Giỏ hàng
    Route::get('/gio-hang', [CartsController::class, 'index'])->name('carts');
    Route::post('/gio-hang', [CartsController::class, 'updateQuantity'])->name('update-carts');
    Route::post('/them-vao-gio-hang/{id?}', [CartsController::class, 'addCart'])->name('add-cart');
    Route::post('/xoa-san-pham-gio-hang/{id?}', [CartsController::class, 'deleteProductCart'])->name('delete-product-cart');

    // Thanh toán / Đặt hàng
    Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/thanh-toan', [CheckoutController::class, 'create'])->name('post-checkout');

    // Quản lý đơn mua
    Route::get('/don-mua', [OrderedController::class, 'index'])->name('ordered');
    Route::post('/don-mua/{id?}', [OrderedController::class, 'updateStatus'])->name('update-status');
    Route::post('/huy-don-mua/{id?}', [OrderedController::class, 'cancelOrder'])->name('cancel-order');

    // Thông tin cá nhân & đổi mật khẩu
    Route::get('/thong-tin-ca-nhan', [InforController::class, 'index'])->name('information');
    Route::post('/cap-nhat-thong-tin', [InforController::class, 'updateInfor'])->name('update-infor');
    Route::post('/doi-mat-khau', [InforController::class, 'changePass'])->name('change-password');
});


// ==================== ROUTE ADMIN ====================
Route::prefix('admin')->group(function () {
    // Đăng nhập / Đăng xuất Admin
    Route::get('/', [LoginAdminController::class, 'index'])->name('admin.login');
    Route::post('/', [LoginAdminController::class, 'login'])->name('admin.post-login');
    Route::get('/logout', [LoginAdminController::class, 'logout'])->name('admin.logout');

    // Các route yêu cầu quyền Quản trị viên
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/dashboard/quick-approve/{id}', [DashboardController::class, 'quickApprove'])->name('admin.quick-approve');

        // Quản lý Đơn hàng
        Route::get('/orders', [OrderAdminController::class, 'index'])->name('admin.orders.index');
        Route::post('/orders/update-status/{id}', [OrderAdminController::class, 'updateStatus'])->name('admin.orders.update-status');
        Route::get('/orders/print/{id}', [OrderAdminController::class, 'printInvoice'])->name('admin.orders.print');
    });
});

