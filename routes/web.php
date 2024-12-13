<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{pathMatch}',function(){
    return view('welcome');
})->where('pathMatch', ".*");

Route::resource('invoices', InvoiceController::class);

