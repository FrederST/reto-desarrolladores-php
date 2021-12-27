<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('customers', CustomerController::class)->except(['create', 'edit', 'show'])
->middleware(['auth:sanctum', 'verified', 'role:admin']);

Route::resource('products', ProductController::class)->except(['create', 'edit', 'show'])
->middleware(['auth:sanctum', 'verified', 'role:admin']);

Route::group(['prefix' => 'productImages'], function () {

    Route::post('upload/{productId}', [ProductImageController::class, 'upload'])->name('products.images.upload');
    Route::delete('{productImage}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');

});
