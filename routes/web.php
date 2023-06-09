<?php

use Faker\Guesser\Name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OngkirController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\HomeAdminController;
use App\Http\Controllers\NewsLetterController;
use App\Models\OrderProduct;
use App\Models\User;

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
    if (Auth::check() && Auth::user()->level == 'user') {
        return redirect()->route('customer.index');
    }
    return view('welcome');
});

Route::get('/onsale', [HomeController::class, 'sale'])->name('onsale');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/coomingsoon', [HomeController::class, 'coomingsoon'])->name('coomingsoon');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/detail_product/{id}', [HomeController::class, 'details'])->name('detail.product');
Route::get('/howtoorder', [HomeController::class, 'howtoorder'])->name('howtoorder');
Route::get('/tmc', [HomeController::class, 'tmc'])->name('tmc');

Route::group(['middleware' => ['auth', 'level:admin']], function () {

    // Dashboard //
    Route::get('/dashboard', [HomeAdminController::class, 'index']);
    // End Dashboard //

    // Data Product //
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/add_product', [ProductController::class, 'create'])->name('product.create');
    Route::post('/add', [ProductController::class, 'store'])->name('product.store');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::patch('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/sale', [ProductController::class, 'discountedProducts'])->name('discounted.products');
    // End Product //

    // Data User //
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/detail/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/my_profile', [UserController::class, 'profile'])->name('profile.show');
    // End Data User //

    // Tabel Order //
    Route::get('/user_order', [PesananController::class, 'index'])->name('index');
    Route::get('/user_order/edit/{id}', [PesananController::class, 'edit'])->name('edit.pesanan');
    Route::patch('/user_order/update/{id}', [PesananController::class, 'update'])->name('update.pesanan');
    Route::get('/detail_order/{id}', [HomeAdminController::class, 'detail_order'])->name('detail.order');
    Route::get('/generate/pdf/{id}', [PesananController::class, 'generatePDF'])->name('export.pdf');
    Route::get('/export/order/{id}', [HomeAdminController::class, 'export_order_pdf'])->name('exportorder.pdf');
    Route::get('/order_admin', [HomeAdminController::class, 'order'])->name('order.admin');
    // End Order //

    // Sales //
    Route::get('/sales_admin', [HomeAdminController::class, 'sales'])->name('sales.admin');
    Route::get('/detail_sales/{id}', [HomeAdminController::class, 'detail_sales'])->name('detail.sales');
    // End sales //

    // Export Excel //
    Route::get('/export/sales/excel', [HomeAdminController::class, 'export'])->name('export.excel');
    Route::get('/export/order/excel', [HomeAdminController::class, 'export_order'])->name('export.order');
    // End Excel //

    // Subscribe //
    Route::get('/newsletter', [NewsLetterController::class, 'index'])->name('subscribe.index');
    // End subscribe //
});


// USER //
Route::group(['middleware' => ['auth', 'level:user']], function () {
    Route::get('/customer', [HomeController::class, 'showPage'])->name('customer.index');
    Route::get('/add-to-cart/{id}', [CartController::class, 'store'])->name('store');
    Route::get('/on_sale', [HomeController::class, 'onsale'])->name('customer.onsale');
    Route::get('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.index');

    // Order //
    Route::get('/checkout', [OrderController::class, 'index'])->name('customer.checkout');
    Route::post('/add_order', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/checkout/{province_id}', [OrderController::class, 'getCities']);
    Route::post('/ongkir', [OrderController::class, 'check_ongkir']);
    Route::get('/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('/add_orders', [OrderController::class, 'add_order'])->name('store.checkout');
    Route::get('/ongkirs', [OrderController::class, 'orders'])->name('ongkir.order');
    Route::get('/clear_payment/{order}', [OrderController::class, 'showClearPayment'])->name('clear.payment');
    Route::get('/unggah_bukti_bayar/edit/{id}', [OrderController::class, 'edit'])->name('edit.buktitf');
    Route::post('/unggah_bukti_bayar/update/{id}', [OrderController::class, 'update'])->name('update.payment');

    // End Order //

    // Pesanan //
    Route::get('/pesanan', [OrderController::class, 'pesanan'])->name('cek.pesanan');
    Route::get('/detail_pesanan/{id}', [OrderController::class, 'detail_pesanan'])->name('detail.pesanan');

    // Detail Produk //
    Route::get('/detail_product/{id}', [HomeController::class, 'details'])->name('detail.product');
    // End detail produk

    // Wishlist //
    Route::get('/wishlist', [WishListController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/{id}', [WishListController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/add_wishlist/{id}', [WishListController::class, 'store'])->name('wishlist.store');
    // end wishlist //

    // Detail User //
    Route::get('/detail_user/{id}', [UserController::class, 'detail_user'])->name('detail.profile');
    Route::get('/edit_user/{id}', [UserController::class, 'edit_user'])->name('edit.profile');
    Route::patch('/edit_user/update/{id}', [UserController::class, 'update_profile'])->name('update.profile');
    // End User //

    // Newsletter //
    Route::post('/subscribe', [NewsLetterController::class, 'store'])->name('subscribe');
    // End newsletter

    // Contact us //
    Route::post('/message', [HomeController::class, 'store'])->name('message');
});



    // END USER //