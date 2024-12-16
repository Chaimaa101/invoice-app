<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;


Route::get('/invoices/newInvoice', [InvoiceController::class, 'newInvoice']);
Route::resource('invoices', InvoiceController::class);


Route::get('/{pathMatch}',function(){
    return view('welcome');
})->where('pathMatch', ".*");



