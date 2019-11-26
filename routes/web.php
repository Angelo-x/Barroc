<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/sales', 'SalesController@dashboard')->name('sales');
Route::get('/productpage', 'productpage@dashboard')->name('productpage');
Route::get('/contactformulier', 'contactformulier@dashboard')->name('contactformulier');

Route::get('/register', function (){return 'ik mag hier komen...';})->middleware('auth','role:3');

Route::resource('quotes', 'QuoteController');
Route::resource('customers', 'CustomerController');

