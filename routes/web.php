<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes([
    'register' => false,
    // 'login' => false,
    'reset' => false,
]);

Route::middleware('auth')->group(function () {
Route::get('/', 'InvoiceController@index')->name('home');

Route::get('/userguide', 'SecurityController@userguide')->name('userguide');
Route::match(['get', 'post'], '/security', 'SecurityController@security')->name('security');
Route::post('/security', 'SecurityController@change_email')->name('security.notifikasi');

// PRODUCT
Route::prefix('product')->group(function () {
    Route::get('/datatable', 'ProductController@datatable')->name('product.datatable');
});
Route::resource('product', 'ProductController');

// PRICE
Route::prefix('price')->group(function () {
    Route::get('/datatable', 'PriceController@datatable')->name('price.datatable');
});
Route::resource('price', 'PriceController');

// PRICE SINGLE
Route::prefix('pricesingle')->group(function () {
    Route::get('/datatable', 'PriceSingleController@datatable')->name('pricesingle.datatable');
});
Route::resource('pricesingle', 'PriceSingleController');

// SINGLE
Route::prefix('single')->group(function () {
    Route::post('/sync', 'SingleController@sync')->name('single.sync');
    Route::get('/datatable', 'SingleController@datatable')->name('single.datatable');
});
Route::resource('single', 'SingleController');

// INVOICE
Route::prefix('invoice')->group(function () {
    Route::get('/datatable', 'InvoiceController@datatable')->name('invoice.datatable');
    Route::get('/getProducts', 'InvoiceController@getProducts')->name('invoice.getProducts');
    Route::get('/getProductDetails', 'InvoiceController@getProductDetails')->name('invoice.getProductDetails');
    Route::get('/getCustomerDetails', 'InvoiceController@getCustomerDetails')->name('invoice.getCustomerDetails');
    Route::get('/previewdynamic/{id}/{download?}', 'InvoiceController@previewdynamic')->name('invoice.previewdynamic');
});
Route::resource('invoice', 'InvoiceController');

// CUSTOMER
Route::prefix('customer')->group(function () {
    Route::get('/datatable', 'CustomerController@datatable')->name('customer.datatable');
});
Route::resource('customer', 'CustomerController');
});