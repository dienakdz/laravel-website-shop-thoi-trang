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
use App\Http\Controllers\admin\ProductAdminController;
use App\Http\Controllers\admin\CategoryAdminController;
use App\Http\Controllers\admin\CustomerAdminController;
use App\Http\Controllers\admin\ContactAdminController;
use App\Http\Controllers\admin\BannerAdminController;

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
        Route::get('/orders/detail-json/{id}', [OrderAdminController::class, 'getOrderDetailJson'])->name('admin.orders.detail-json');
        Route::post('/orders/update-status/{id}', [OrderAdminController::class, 'updateStatus'])->name('admin.orders.update-status');
        Route::get('/orders/print/{id}', [OrderAdminController::class, 'printInvoice'])->name('admin.orders.print');

        // Quản lý Sản phẩm
        Route::get('/products', [ProductAdminController::class, 'index'])->name('admin.products.index');
        Route::get('/products/create', [ProductAdminController::class, 'create'])->name('admin.products.create');
        Route::post('/products/store', [ProductAdminController::class, 'store'])->name('admin.products.store');
        Route::get('/products/edit/{id}', [ProductAdminController::class, 'edit'])->name('admin.products.edit');
        Route::post('/products/update/{id}', [ProductAdminController::class, 'update'])->name('admin.products.update');
        Route::post('/products/delete/{id}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy');

        // Quản lý Danh mục & Thương hiệu
        Route::get('/categories', [CategoryAdminController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories/store', [CategoryAdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::post('/categories/update/{id}', [CategoryAdminController::class, 'updateCategory'])->name('admin.categories.update');
        Route::post('/categories/delete/{id}', [CategoryAdminController::class, 'destroyCategory'])->name('admin.categories.destroy');

        Route::post('/brands/store', [CategoryAdminController::class, 'storeBrand'])->name('admin.brands.store');
        Route::post('/brands/update/{id}', [CategoryAdminController::class, 'updateBrand'])->name('admin.brands.update');
        Route::post('/brands/delete/{id}', [CategoryAdminController::class, 'destroyBrand'])->name('admin.brands.destroy');

        // Quản lý Khách hàng
        Route::get('/customers', [CustomerAdminController::class, 'index'])->name('admin.customers.index');
        Route::post('/customers/toggle-status/{id}', [CustomerAdminController::class, 'toggleStatus'])->name('admin.customers.toggle-status');

        // Hòm thư Liên hệ
        Route::get('/contacts', [ContactAdminController::class, 'index'])->name('admin.contacts.index');
        Route::post('/contacts/mark-read/{id}', [ContactAdminController::class, 'markRead'])->name('admin.contacts.mark-read');
        Route::post('/contacts/delete/{id}', [ContactAdminController::class, 'destroy'])->name('admin.contacts.destroy');

        // Quản lý Banner Trang chủ
        Route::get('/banners', [BannerAdminController::class, 'index'])->name('admin.banners.index');
        Route::post('/banners/store', [BannerAdminController::class, 'store'])->name('admin.banners.store');
        Route::post('/banners/update/{id}', [BannerAdminController::class, 'update'])->name('admin.banners.update');
        Route::post('/banners/set-active/{id}', [BannerAdminController::class, 'setActive'])->name('admin.banners.set-active');
        Route::post('/banners/delete/{id}', [BannerAdminController::class, 'destroy'])->name('admin.banners.destroy');
    });
});


