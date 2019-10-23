<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Auth\User\AccountController;
use App\Http\Controllers\Backend\Auth\User\ProfileController;
use \App\Http\Controllers\Backend\Auth\User\UpdatePasswordController;


//===== Catalog =====//
//===== Product =====//
Route::get('catalog/product-management/{id?}', ['uses' => 'Admin\Catalog\ProductsController@index', 'as' => 'catalog.product-management']);
Route::get('catalog/all-products', ['uses' => 'Admin\Catalog\ProductsController@_all_products', 'as' => 'catalog.all-products']);

Route::get('ajax/catalog/get-product', 'Admin\Catalog\ProductsController@get_product');
Route::get('ajax/catalog/delete-product', 'Admin\Catalog\ProductsController@delete_product');
Route::get('ajax/catalog/delete-product-image', 'Admin\Catalog\ProductsController@delete_product_image');
Route::put('ajax/catalog/set-main-image', 'Admin\Catalog\ProductsController@set_main_image');

Route::post('ajax/catalog/add-product','Admin\Catalog\ProductsController@add_product');
Route::post('ajax/catalog/add-product-images','Admin\Catalog\ProductsController@add_product_images');


Route::get('ajax/catalog/get-shipping','Admin\Catalog\ShippingController@get_shipping');
Route::post('ajax/catalog/add-shipping','Admin\Catalog\ShippingController@add_shipping');
Route::post('ajax/catalog/add-shipping','Admin\Catalog\ShippingController@add_shipping');
Route::delete('ajax/catalog/delete-shipping','Admin\Catalog\ShippingController@delete_shipping');

