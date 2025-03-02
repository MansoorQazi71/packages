<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('order_form');
});

Route::get('/admin/settings', function () {
    return view('admin.settings');
})->middleware('auth');

Route::post('/admin/settings/update', [AdminController::class, 'updateSettings'])->middleware('auth');
