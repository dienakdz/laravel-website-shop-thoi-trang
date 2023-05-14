<?php



use App\Http\Controllers\clients\InforController;
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


//Admin
use App\Http\Controllers\admin\LoginAdminController;

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

//Xử lý bên clients

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [ProductsController::class, 'index'])->name('product');
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'postContact'])->name('post-contact');
Route::get('/gio-hang', [CartsController::class, 'index'])->name('carts');
Route::post('/gio-hang', [CartsController::class, 'updateQuantity'])->name('update-carts');
Route::post('/xoa-san-pham-gio-hang/{id?}', [CartsController::class, 'deleteProductCart'])->name('delete-product-cart');
Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/thanh-toan', [CheckoutController::class, 'create'])->name('post-checkout');
Route::get('/chi-tiet-san-pham/{id?}', [DetailProductController::class, 'index'])->name('detail-product');
Route::post('/danh-gia-san-pham/{id?}', [DetailProductController::class, 'postRating'])->name('rating-product');
Route::post('/them-vao-gio-hang/{id?}', [CartsController::class, 'addCart'])->name('add-cart');
Route::get('/tai-khoan', [LoginController::class, 'index'])->name('login');
Route::post('/postLogin', [LoginController::class, 'postLogin'])->name('postLogin');
Route::post('/tai-khoan', [LoginController::class, 'create'])->name('newUser');
Route::get('/logout', [LoginController::class, 'getLogout'])->name('logout');
Route::get('/don-mua', [OrderedController::class, 'index'])->name('ordered');
Route::post('/don-mua/{id?}', [OrderedController::class, 'updateStatus'])->name('update-status');
Route::get('/tim-kiem-san-pham/{search?}', [ProductsController::class, 'search'])->name('search');
Route::get('/gioi-thieu', [AboutController::class, 'index'])->name('about');
Route::get('/thong-tin-ca-nhan', [InforController::class, 'index'])->name('information');
Route::post('/cap-nhat-thong-tin', [InforController::class, 'updateInfor'])->name('update-infor');
Route::post('/doi-mat-khau', [InforController::class, 'changePass'])->name('change-password');

//Xử lý cho admin
Route::prefix('admin')->group(function () {
    Route::get('/', [LoginAdminController::class, 'index'])->name('admin.login');
    Route::post('/', [LoginAdminController::class, 'login'])->name('admin.login');
});

