<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountsControllerResource;
use App\Http\Controllers\AdditionalDescriptionControllerResource;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\AdsControllerResource;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryControllerResource;
use App\Http\Controllers\FavouritControllerResource;
use App\Http\Controllers\ForgetPassword;
use App\Http\Controllers\InvoiceControllerResource;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialControllerResource;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\MaterialsListController;
use App\Http\Controllers\NotificationContoller;
use App\Http\Controllers\NotificationControllerResource;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderControllerResource;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductControllerResource;
use App\Http\Controllers\ProductSizesController;
use App\Http\Controllers\ProductSizesControllerResource;
use App\Http\Controllers\ProductsPhotoControllerResource;
use App\Http\Controllers\ProductsPhotosController;
use App\Http\Controllers\RateControllerResource;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleControllerResource;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\SizesControllerResource;
use App\Http\Controllers\StateControllerResource;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StoreAddDescController;
use App\Http\Controllers\StoreAddDescControllerResource;
use App\Http\Controllers\StoreControllerResource;
use App\Http\Controllers\StoreProdPhotControllerResource;
use App\Http\Controllers\StoreProductsController;
use App\Http\Controllers\StoreProductsControllerResource;
use App\Http\Controllers\UserAccountControllerResource;
use App\Http\Controllers\UserControllerResource;
use App\Http\Controllers\UserOrderControllerResource;
use App\Http\Controllers\VerificationController;
// use App\Http\Controllers\VerificationControllerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


const BASE = 'v1/user';
const GUEST = 'v1/public';
const ADMIN = 'v1/admin';

// Route::get(GUEST . 'facebook/redirect', function () {
//     return Socialite::driver('facebook')->redirect();
// });

// Route::get(GUEST . 'facebook/callback', function () {
//     $user = Socialite::driver('facebook')->user();

//     // $user->token
// });

Route::post(BASE . '/login', [LoginController::class, 'Login']);
Route::post(GUEST . '/accountCheck', [LoginController::class, 'accountCheck']);
Route::post(BASE . '/register', [RegisterController::class, 'register']);
Route::post(BASE . '/forgetPassword', [ForgetPassword::class, 'forgetPassword']);
Route::post(BASE . '/newPassword', [ForgetPassword::class, 'newPassword']);
Route::get(BASE . '/filter', [StatisticsController::class, 'productsFilter']);

// public routes
Route::group(['prefix' => GUEST], function () {
    // verifications
    Route::post('verifyAccount', [VerificationController::class, 'verifyAccount']);

    // get categories and sub categories
    Route::get('getCategories', [CategoryController::class, 'getCategories']);
    Route::get('getSubCategories', [CategoryController::class, 'getSubCategories']);

    /* suggestion part */
    // Suggestions from products
    Route::get('suggestions', [ProductController::class, 'suggestions']);
    // top slider
    Route::get('topSuggestions', [ProductController::class, 'topSuggestions']);
    // web suggestions
    Route::get('webSuggestions', [ProductController::class, 'webSuggestions']);
    // new stuff suggestion
    Route::get('ourNew', [ProductController::class, 'ourNew']);
    // discount offers suggestion
    Route::get('tempOffers', [ProductController::class, 'tempOffers']);
    // latest suggestion
    Route::get('latest', [ProductController::class, 'latest']);

    // product page
    Route::get('initItems', [ProductController::class, 'initItems']);

    // search product with filter
    Route::post('search', [SearchController::class, 'search']);
    Route::post('webSearch', [SearchController::class, 'webSearch']);

    // products
    Route::ApiResource('products', ProductControllerResource::class)->only('index', 'show');
    Route::get('top', [ProductController::class, 'topProduct']);
    Route::get('todayProducts', [ProductController::class, 'todayProducts']);
    Route::get('adProducts', [ProductController::class, 'adProducts']);
    Route::get('productsWith', [ProductController::class, 'productsWith']);
    Route::get('androidProductsWith', [ProductController::class, 'AndroidProductsWith']);
    Route::post('getProducts', [ProductController::class, 'getProducts']);
    Route::get('randomProducts', [ProductController::class, 'randomProducts']);

    // additional Description
    Route::ApiResource('additionalDescription', AdditionalDescriptionControllerResource::class)->only('index');

    // products photos
    Route::ApiResource('productsPhotos', ProductsPhotoControllerResource::class)->only('index');

    // states
    Route::apiResource('states', StateControllerResource::class)->only('index');

    // categories
    Route::apiResource('categories', CategoryControllerResource::class)->only('index', 'show');
    Route::get('categoryList', [CategoryController::class, 'categoryList']);

    // sizes
    Route::apiResource('sizes', SizesControllerResource::class)->only('index');
    Route::get('getSizesBy', [SizesController::class, 'getSizesBy']);

    // materials list
    Route::apiResource('materialsLists', MaterialsListController::class)->only('index');

    // ads
    Route::apiResource('ads', AdsControllerResource::class)->only('index');
    Route::get('adsOptions', [AdsController::class, 'AdsOptions']);
    Route::get('adsGroupBy', [AdsController::class, 'AdsGroupBy']);
    Route::get('randomAd', [AdsController::class, 'randomAd']);
});

Route::group(['middleware' => 'auth.jwt'], function () {

    Route::group(['prefix' => BASE, 'middleware' => 'userWare'], function () {

        // user personal
        Route::get('profile', [LoginController::class, 'profile']);
        Route::post('logout', [LoginController::class, 'logout']);
        Route::put('resetPassword', [LoginController::class, 'resetPassword']);
        Route::post('updateProfile', [LoginController::class, 'updateProfile']);

        // user favourit
        Route::apiResource('favourit', FavouritControllerResource::class)->except('update');

        // user orders
        Route::apiResource('order', UserOrderControllerResource::class)->except('destroy', 'update');
        Route::get('myOrders', [OrderController::class, 'myOrders']);
        Route::get('orders', [OrderController::class, 'orders']);
        Route::get('webOrders', [OrderController::class, 'webOrders']);
        Route::get('getMyOrder', [OrderController::class, 'getMyOrder']);
        Route::get('getMyOrderByNumber', [OrderController::class, 'getMyOrderByNumber']);
        Route::get('ordersDetails', [OrderController::class, 'ordersDetails']);

        // user account
        Route::apiResource('accounts', UserAccountControllerResource::class);

        // user invoices
        Route::get('myInvoices', [InvoicesController::class, 'myInvoices']);

        // rate product
        Route::apiResource('rate', RateControllerResource::class)->except('store', 'destroy');

        // rate notification
        Route::apiResource('myNotifications', NotificationControllerResource::class)->only('index');
        Route::get('readOne/{id}', [NotificationContoller::class, 'readOne']);
        Route::get('readAll', [NotificationContoller::class, 'readAll']);
    });

    Route::group(['prefix' => BASE, 'middleware' => 'storeWare'], function () {

        // store product
        Route::post('updateProduct/{product}', [StoreProductsController::class, 'update']);
        Route::ApiResource('product', StoreProductsControllerResource::class)->except('update');

        // store additional description
        Route::apiResource('storeAdditionalDescription', StoreAddDescControllerResource::class);
        Route::get('storeAdditionalDescriptionBy/{productID}', [StoreAddDescController::class, 'storeAdditionalDescriptionBy']);

        // store product Photo
        Route::apiResource('storeProductPhotos', StoreProdPhotControllerResource::class)->except('update');
        Route::post('storeProductPhoto/{storeProductPhoto}', [StoreProdPhotControllerResource::class, 'update']);
        Route::get('storeProductPhotosBy/{productsID}', [ProductsPhotosController::class, 'storeProductPhotosByID']);

        // store Invoices
        Route::get('storeInvoices', [InvoicesController::class, 'storeInvoices']);

        // my profile
        Route::get('storeProfile', [LoginController::class, 'storeProfile']);

        // notification
        Route::apiResource('storeNotifications', NotificationControllerResource::class)->only('index');

        // order by order id
        Route::get('storeOrders', [OrderController::class, 'storeOrders']);
        Route::get('allStoreOrders', [OrderController::class, 'allStoreOrders']);

        // products sizes
        Route::ApiResource('productSizes', ProductSizesControllerResource::class);
        Route::get('productSize/{id}', [ProductSizesController::class, 'sizesByProductID']);

        // materials
        Route::apiResource('materials', MaterialControllerResource::class);
        // Route::get('getMaterialsBy', [MaterialsController::class, 'getMaterialsBy']);
    });

    Route::group(['prefix' => ADMIN, 'middleware' => 'adminWare'], function () {

        // statistics
        Route::get('statistics', [StatisticsController::class, 'statistics']);

        // users
        Route::apiResource('users', UserControllerResource::class)->except('update');
        Route::post('user/{user}', [UserControllerResource::class, 'update']);

        // stores
        Route::ApiResource('stores', StoreControllerResource::class)->except('update');
        Route::post('store/{store}', [StoreControllerResource::class, 'update']);
        Route::get('storeProducts', [StoreProductsController::class, 'storeProducts']);

        // products
        Route::ApiResource('products', ProductControllerResource::class)->except('store');

        // additional Description
        Route::ApiResource('additionalDescription', AdditionalDescriptionControllerResource::class);

        // products photos
        Route::ApiResource('productsPhotos', ProductsPhotoControllerResource::class)->except('update');
        Route::post('productPhoto/{ProductsPhoto}', [ProductsPhotoControllerResource::class, 'update']);

        // // products sizes
        // Route::ApiResource('productSizes', ProductSizesControllerResource::class);
        // Route::get('productSize/{id}', [ProductSizesController::class, 'sizesByProductID']);

        // accounts
        Route::resource('accounts', AccountsControllerResource::class);

        // invoices
        Route::ApiResource('invoices', InvoiceControllerResource::class)->only('index', 'show');

        // orders
        Route::apiResource('orders', OrderControllerResource::class)->except('destroy', 'store');
        // order by order id
        Route::get('adminOrders', [OrderController::class, 'adminOrders']);

        // states
        Route::apiResource('states', StateControllerResource::class);

        // categories
        Route::apiResource('categories', CategoryControllerResource::class)->except('update');
        Route::post('category/{category}', [CategoryControllerResource::class, 'update']);

        // sizes
        Route::apiResource('sizes', SizesControllerResource::class);

        // // materials
        // Route::apiResource('materials', MaterialControllerResource::class);
        // materials list
        Route::apiResource('materialsList', MaterialsListController::class);

        // roles
        Route::apiResource('roles', RoleControllerResource::class);
        Route::post('givePermission', [RoleController::class, 'givePermission']);

        // ads
        Route::apiResource('ads', AdsControllerResource::class)->except('update');
        Route::post('ad/{ad}', [AdsControllerResource::class, 'update']);

        // Notifications
        Route::apiResource('notifications', NotificationControllerResource::class)->except('index');
    });
});
