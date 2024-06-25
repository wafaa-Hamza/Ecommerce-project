<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\ShippingCountryController;
use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\CartController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\FlashSaleController;
use App\Http\Controllers\API\V1\FlashSaleItemController;
use App\Http\Controllers\API\V1\MessageController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\RatingController;
use App\Http\Controllers\API\V1\SettingController;
use App\Http\Controllers\API\V1\ShippingController;
use App\Http\Controllers\API\V1\StatsController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\WishListController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Laravel\Telescope\Http\Controllers\HomeController;





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

// Auth Routes are in auth.php
//

Route::prefix('admin')->name('admin.')->middleware('auth:api')->group(function () {
       Route::apiResource("products", AdminProductController::class );
       Route::get("active", [AdminProductController::class, "active"]);
});




Route::get("category/show-sub-category", [CategoryController::class , 'show_sub_category']);
    Route::post("user/update_profile", [UserController::class , 'update_profile']);
    Route::get("user/my_profile", [UserController::class , 'my_profile']);

        Route::apiResource("rating", RatingController::class , ['only' => ['store' , 'destroy']]);
        Route::apiResource("wishlist", WishListController::class , ['only' => ['index','store' ,'destroy']]);
        Route::get("/cart/my-cart", [CartController::class, "show"]);
        Route::post("/cart/updateCart" , [WishListController::class, "update"]);
        Route::get("/cart/add-to-cart", [CartController::class, "store"]);
        Route::delete("/cart/remove-from-cart", [CartController::class, "destroy"]);
        Route::post("/order/my_order", [OrderController::class, "my_order"]);
        Route::apiResource("order", OrderController::class , ['only' => ['store' , 'destroy']]);
        Route::get("/message/send_as_client", [MessageController::class, "send_as_client"]);


    Route::get("message/index_chat_messages", [MessageController::class,"index_chat_messages"]);

        Route::apiResource("order", OrderController::class , ['only' => ['show']]);




        Route::get("stats", [StatsController::class , 'index']);

        Route::apiResource("category", CategoryController::class , ['except' => ['index', 'show']]);
        Route::get("category/show-admin", [CategoryController::class , 'show_admin' ]);

        Route::apiResource("order", OrderController::class , ['only' => ['index' , 'update']]);

        Route::apiResource("product", ProductController::class , ['except' => ['index', 'show']]);


        Route::apiResource("user", UserController::class);

        Route::apiResource("message", MessageController::class)->only(['index', 'store']);
        Route::get("message/index_chat_messages", [MessageController::class,"index_chat_messages"]);


        Route::apiResource("shipping", ShippingController::class , ['only' => [ 'store' , 'update', 'destroy']]);


        Route::apiResource("setting", SettingController::class , ['only' => ['store']]);
        Route::apiResource("flashSale", FlashSaleController::class , ['only' => ['store'  , 'index', 'destroy']]);
        Route::apiResource("flashSaleItem", FlashSaleItemController::class , ['only' => ['store', 'update', 'destroy']]);

Route::apiResource("shipping", ShippingController::class , ['only' => [ 'index']]);
Route::apiResource("product", ProductController::class);
Route::apiResource("category", CategoryController::class , ['only' => ['index', 'show']]);
Route::apiResource("rating", RatingController::class , ['only' => ['index' , 'update']]);
Route::apiResource("wishlist", WishListController::class , ['only' => ['update']]);
Route::apiResource("setting", SettingController::class , ['only' => ['index']]);

Route::post('send-notification', [NotificationController::class, 'sendNotificationToAllUsers']);
Route::get('dashboard-shipping-countries', [ShippingCountryController::class, 'index'])->name('shipping-countries.index');
Route::post('dashboard-shipping-countries', [ShippingCountryController::class, 'store'])->name('shipping-countries.store');
