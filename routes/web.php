<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::resource('invoices', InvoiceController::class);
Route::resource('customers', CustomerController::class);
Route::resource('products', ProductController::class);
Route::resource('invoiceItems', InvoiceItemController::class);


Route::get('/{pathMatch}',function(){
    return view('welcome');
})->where('pathMatch', ".*");



