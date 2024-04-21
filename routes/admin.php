<?php

use App\Events\LocationEvent;
use App\Events\Login;
use App\Jobs\EmailJob;
use App\Mail\EmailVerification;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Admin
Route::prefix("admin-auth")->group(function () {
    Route::post("login", "AuthController@login");
    Route::get("veriy-token", "AuthController@verifyToken");
    Route::get("logout", "AuthController@logout");
});

Route::prefix("items")->group(function () {
    Route::post("", "ItemController@store");
    Route::post("update", "ItemController@update");
    Route::delete("{id}", "ItemController@delete");
    Route::get("", "ItemController@index");
});


Route::prefix("taxes")->group(function () {
    Route::post("", "TaxController@store");
    Route::post("update", "TaxController@update");
    Route::delete("{id}", "TaxController@delete");
    Route::get("", "TaxController@index");
    Route::get("toggle-status/{id}", "TaxController@toggleStatus");
    Route::get("{id}", "TaxController@find");
});

Route::prefix("variations")->group(function () {
    Route::post("", "VariationController@store");
    Route::post("update", "VariationController@update");
    Route::delete("{id}", "VariationController@delete");
    Route::get("", "VariationController@index");
    Route::get("toggle-status/{id}", "VariationController@toggleStatus");
    Route::get("{id}", "VariationController@find");
});

Route::prefix("categories")->group(function () {
    Route::post("", "CategoryController@store");
    Route::post("update", "CategoryController@update");
    Route::delete("{id}", "CategoryController@delete");
    Route::get("", "CategoryController@index");
    Route::get("brands", "CategoryController@getBrands");
    Route::get("base-categories", "CategoryController@getBaseCategories");
    Route::get("{id}", "CategoryController@find");
});

Route::prefix("variation-values")->group(function () {
    Route::post("", "VariationValueController@store");
    Route::post("update", "VariationValueController@update");
    Route::delete("{id}", "VariationValueController@delete");
    Route::get("", "VariationValueController@index");
    Route::get("toggle-status/{id}", "VariationValueController@toggleStatus");
    Route::get("{id}", "VariationValueController@find");
});

Route::prefix("units")->group(function () {
    Route::post("", "UnitController@store");
    Route::post("update", "UnitController@update");
    Route::delete("{id}", "UnitController@delete");
    Route::get("", "UnitController@index");
    Route::get("toggle-status/{id}", "UnitController@toggleStatus");
    Route::get("{id}", "UnitController@find");
});

Route::prefix("brands")->group(function () {
    Route::post("", "BrandController@store");
    Route::post("update", "BrandController@update");
    Route::delete("{id}", "BrandController@delete");
    Route::get("", "BrandController@index");
    Route::get("toggle-status/{id}", "BrandController@toggleStatus");
    Route::get("{id}", "BrandController@find");
});

Route::prefix("customers")->group(function () {
    Route::post("", "CustomerController@store");
    Route::post("update", "CustomerController@update");
    Route::delete("{id}", "CustomerController@delete");
    Route::get("", "CustomerController@index");
    Route::get("toggle-status/{id}", "CustomerController@toggleStatus");
    Route::get("{id}", "CustomerController@find");
});

Route::prefix("locations")->group(function () {
    Route::post("", "LocationController@store");
    Route::post("update", "LocationController@update");
    Route::delete("{id}", "LocationController@delete");
    Route::get("", "LocationController@index");
    Route::get("toggle-publish/{id}", "LocationController@togglePublish");
    Route::get("toggle-default/{id}", "LocationController@toggleDefault");
    Route::get("{id}", "LocationController@find");
});


Route::prefix("many-image")->group(function () {
    Route::post("", "ManyImageController@store");
    Route::post("update", "ManyImageController@update");
    Route::post("add-image", "ManyImageController@addImage");
    Route::delete("delete-image", "ManyImageController@deleteImage");
    Route::delete("{id}", "ManyImageController@delete");
    Route::get("", "ManyImageController@index");
});


Route::prefix("media-manager")->group(function () {
    Route::post("", "MediaManagerController@store");
    Route::get("", "MediaManagerController@getAllMedia");
    Route::delete("{id}", "MediaManagerController@deleteMedia");
});

Route::prefix("tags")->group(function () {
    Route::post("", "TagController@store");
    Route::post("update", "TagController@update");
    Route::delete("{id}", "TagController@delete");
    Route::get("", "TagController@index");
    Route::get("{id}", "TagController@find");
});

Route::prefix("products")->group(function () {
    Route::post("", "ProductController@store");
    Route::post("update", "ProductController@update");
    Route::delete("{id}", "ProductController@delete");
    Route::get("", "ProductController@index");
    //Drop down list
    Route::get("categories", "ProductController@getCategories");
    Route::get("brands", "ProductController@getBrands");
    Route::get("units", "ProductController@getUnits");
    Route::get("tags", "ProductController@getTags");
    Route::get("variations", "ProductController@getVariations");
    Route::get("variation-values/{variationId}", "ProductController@getVariationValues");
    Route::get("taxes", "ProductController@getTaxes");
    Route::get("{id}", "ProductController@find");
    Route::get("toggle-publish/{id}", "ProductController@togglePublish");
});

Route::prefix("stocks")->group(function () {
    Route::get("", "StockController@getStocks");
    Route::get("products", "StockController@getProducts");
    Route::get("locations", "StockController@getLocations");
    Route::post("", "StockController@insertStock");
});

Route::prefix("orders")->group(function () {
    Route::get("", "OrderController@getOrders");
    Route::get("logs", "OrderController@getOrderLogs");
    Route::get("products", "OrderController@getProducts");
    Route::get("locations", "OrderController@getLocations");
    Route::get("brands", "OrderController@getBrands");
    Route::get("categories", "OrderController@getCategories");
    Route::get("customers", "OrderController@getCustomers");
    Route::post("create-customer", "OrderController@createCustomer");
    Route::get("complete-order/{id}", "OrderController@completeOrder");
    Route::get("{id}", "OrderController@getOrder");
    Route::post("", "OrderController@storeOrder");
    Route::put("payment-status", "OrderController@updateOrderPaymentStatus");
    Route::put("status", "OrderController@updateOrderStatus");
    Route::put("products-count", "OrderController@getAllProductsCount");
});

Route::get("test", function () {
    $date = Carbon::now();
    echo $date;                      // +13:30
    echo "\n";
});
