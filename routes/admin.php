<?php

use Illuminate\Support\Facades\DB;
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

Route::prefix("many-image")->group(function () {
    Route::post("", "ManyImageController@store");
    Route::post("update", "ManyImageController@update");
    Route::post("add-image", "ManyImageController@addImage");
    Route::delete("delete-image", "ManyImageController@deleteImage");
    Route::delete("{id}", "ManyImageController@delete");
    Route::get("", "ManyImageController@index");
});
