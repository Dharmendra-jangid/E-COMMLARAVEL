<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();
//home page
Route::get('/',[HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class,'index'])->name('shop.index');
Route::get('/shop/{product_slug}',[ShopController::class,'product_details'])->name('shop.product.details');

// cart page
Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/add',[CartController::class,'add_to_cart'])->name('cart.add');
Route::put('cart/increase-quantity/{rowId}',[CartController::class,'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('cart/decrease-quantity/{rowId}',[CartController::class,'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('cart/remove/{rowId}',[CartController::class,'remove_item'])->name('cart.item.remove');
Route::delete('cart/clear',[CartController::class,'empty_cart'])->name('cart.clear');


Route::post('/cart/apply-coupon',[CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');


// Wishlist page in Routes

Route::post('/wishlist/add',[WishlistController::class,'add_to_wishlist'])->name('wishlist.add');
Route::get('/wishlist',[WishlistController::class,'index'])->name('wishlist.index');
Route::delete('/wishlist/item/remove/{rowId}',[WishlistController::class,'remove_item'])->name('wishlist.item.remove');
Route::delete('/wishlist/clear',[WishlistController::class,'empty_wishlist'])->name('wishlist.item.clear');
Route::post('wishlist/move-to-cart{rowId}',[WishlistController::class,'move_to_cart'])->name('wishlist.move.to.cart');

// checkout

Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');
Route::post('place-an-order',[CartController::class,'place_an_order'])->name('cart.place.an.order');
Route::get('/order-comfirmation',[CartController::class,'order_confirmation'])->name('cart.order.confirmation');


Route::get('/contact-us',[HomeController::class,'contact'])->name('home.contact');
Route::post('/contact/store',[HomeController::class,'contact_store'])->name('home.contact.store');


Route::get('/search',[HomeController::class,'search'])->name('home.search');


Route::middleware(['auth'])->group(function(){
Route::get('/account-dashboard',[UserController::class, 'index'])->name('user.index');
Route::get('/account-orders',[UserController::class,'orders'])->name('user.orders');
Route::get('/account-order/{order_id}/details',[UserController::class,'order_details'])->name('user.order.details');
Route::put('/account-order/cancel-order',[UserController::class,'order_cancel'])->name('user.order.cancel');

Route::get('account-address',[UserController::class,'address'])->name('user.address');
Route::post('account-address-store',[UserController::class,'store_address'])->name('account.address.store');
Route::get('account-address/edit/{id}',[UserController::class,'addressEdit'])->name('user.address.edit');
Route::post('/account-address-updated/{id}',[UserController::class,'updateaddress'])->name('user.address.update');
Route::get('account-address/delete/{id}',[UserController::class,'addressdelete'])->name('user.address.delete');


Route::get('account-details',[UserController::class,'details'])->name('user.details');
Route::get('/account-add-adress',[UserController::class,'addAdrdess'])->name('user.add.address');


});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin',[AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brand/add',[AdminController::class,'add_brands'])->name('admin.brand.add');
    Route::post('/admin/brand/store',[AdminController::class,'brand_store'])->name('admin.brand.store');
    Route::get('admin/brand/edit/{id}',[AdminController::class,'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update',[AdminController::class,'brand_update'])->name('admin.brand.update');
    Route::get('/admin/brand/delete/{id}',[AdminController::class,'brand_delete'])->name('admin.brand.delete');

    // Category
    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('/admin/category/add',[AdminController::class,'add_category'])->name('admin.category.add');
    Route::post('/admin/category/store',[AdminController::class,'category_store'])->name('admin.category.store');
    Route::get('admin/category/edit/{id}',[AdminController::class,'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update',[AdminController::class,'category_update'])->name('admin.category.update');
    Route::get('/admin/category/delete/{id}',[AdminController::class,'category_delete'])->name('admin.category.delete');


    // Products

    Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/product/add',[AdminController::class,'product_add'])->name('admin.product.add');
    Route::post('/admin/product/store',[AdminController::class,'product_store'])->name('admin.product.store');
    Route::get('admin/product/{id}/edit',[AdminController::class,'product_edit'])->name('admin.produt.edit');
    Route::put('admin/product/update',[AdminController::class,'product_update'])->name('admin.product.update');
    Route::get('/admin/product/delete/{id}',[AdminController::class,'product_delete'])->name('admin.product.delete');



    Route::get('/admin/coupons',[AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add',[AdminController::class,'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store',[AdminController::class,'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/edit/{id}',[AdminController::class,'coupon_edit'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update',[AdminController::class,'coupon_update'])->name('admin.coupon.update');
    Route::get('/admin/coupon/delete/{id}',[AdminController::class,'coupon_delete'])->name('admin.coupon.delete');


    Route::get('/admin/orders',[AdminController::class,'orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details',[AdminController::class,'order_details'])->name('admin.order.details');
    Route::put('/admin/order/update-status',[AdminController::class,'update_order_status'])->name('admin.order.status.update');


    Route::get('/admin/slides',[AdminController::class,'slides'])->name('admin.slides');
    Route::get('/admin/slide/add',[AdminController::class,'slide_add'])->name('admin.slide.add');
    Route::post('admin/slide/store',[AdminController::class,'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/{id}/edit',[AdminController::class,'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update',[AdminController::class,'slide_update'])->name('admin.slide.update');
    Route::get('admin/slide/{id}/delete',[AdminController::class,'slide_delete'])->name('admin.slide.delete');


    Route::get('/admin/contact',[AdminController::class,'contact'])->name('admin.contacts');
    Route::get('/admin/contact/{id}/delete',[AdminController::class,'contact_delete'])->name('admin.contact.delete');






    });