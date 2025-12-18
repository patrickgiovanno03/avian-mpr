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
Route::get('/', 'HomeController@index')->name('home');

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
    Route::get('/print/{id}/{download?}', 'InvoiceController@previewdynamic')->name('invoice.previewdynamic');
});
Route::resource('invoice', 'InvoiceController');

// INVOICE + TT
Route::get('/form/{id}', 'InvoiceController@form')->name('form');

// CUSTOMER
Route::prefix('customer')->group(function () {
    Route::get('/datatable', 'CustomerController@datatable')->name('customer.datatable');
});
Route::resource('customer', 'CustomerController');
// });

// GAJI
Route::prefix('gaji')->group(function () {
    Route::post('/upload', 'GajiController@upload')->name('gaji.upload');
    Route::post('/storeDetail/{id}', 'GajiController@storeDetail')->name('gaji.storeDetail');
    Route::post('/deleteDetail', 'GajiController@deleteDetail')->name('gaji.deleteDetail');
    Route::get('/slip/{id}', 'GajiController@slip')->name('gaji.slip');
    Route::get('/slipAll/{id}', 'GajiController@slipAll')->name('gaji.slipAll');
    Route::get('/datatable', 'GajiController@datatable')->name('gaji.datatable');
    Route::post('/rotateImage', 'GajiController@rotateImage')->name('gaji.rotateImage');
    Route::post('/sendWhatsApp', 'GajiController@sendWhatsApp')->name('gaji.sendWhatsApp');
});
Route::resource('gaji', 'GajiController');

// PEGAWAI
Route::prefix('pegawai')->group(function () {
    Route::get('/getPegawai', 'PegawaiController@getPegawai')->name('pegawai.getPegawai');
    Route::get('/datatable', 'PegawaiController@datatable')->name('pegawai.datatable');
});
Route::resource('pegawai', 'PegawaiController');
});

// TANDA TERIMA
Route::prefix('tt')->group(function () {
    Route::get('/datatable', 'TandaTerimaController@datatable')->name('tt.datatable');
    Route::get('/getProducts', 'TandaTerimaController@getProducts')->name('tt.getProducts');
    Route::get('/getFormDetails', 'TandaTerimaController@getFormDetails')->name('tt.getFormDetails');
    Route::get('/getCustomerDetails', 'TandaTerimaController@getCustomerDetails')->name('tt.getCustomerDetails');
    Route::get('/print/{id}/{download?}', 'TandaTerimaController@previewdynamic')->name('tt.previewdynamic');
});
Route::resource('tt', 'TandaTerimaController');