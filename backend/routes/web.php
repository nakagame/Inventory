<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [ProductController::class, 'index'])->name('index');

    // Product
    Route::group(['prefix' => 'product', 'as' => 'product.'], function() {
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        
        Route::patch('/{id}/update', [ProductController::class, 'update'])->name('update');
        Route::patch('/{id}/update/stock', [ProductController::class, 'updateStock'])->name('updateStock');
        
        Route::delete('/{id}/destroy', [ProductController::class, 'destroy'])->name('destroy');
    });

    // profile
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function() {
        Route::get('/{id}', [UserController::class, 'index'])->name('index');
        Route::patch('/{id}/update', [UserController::class, 'update'])->name('update');
    });

    // admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/search', [AdminController::class, 'searchUser'])->name('searchUser');
        Route::patch('/{id}/update', [AdminController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [AdminController::class, 'destroy'])->name('destroy');
        Route::delete('/{id}/active/force-delete', [AdminController::class, 'forceDeleteActive'])->name('forceDeleteActive');
        Route::delete('/{id}/inactive/force-delete', [AdminController::class, 'forceDeleteInactive'])->name('forceDeleteInactive');
    });
});
