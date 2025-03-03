<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('order_form');
});
Route::get('/order', [OrderController::class, 'create'])->name('order.create');

Route::post('/order/submit', [OrderController::class, 'store'])->name('order.submit');

Route::post('/file/analyze', [FileController::class, 'analyze'])->name('file.analyze');

Route::get('/admin/settings', function () {
    return view('admin.settings');

})->name('admin.settings');
Route::post('/admin/settings/update', [AdminController::class, 'updateSettings'])
    ->name('admin.settings.update');


Route::post('/admin/settings/update', [AdminController::class, 'updateSettings'])->middleware('auth');
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
