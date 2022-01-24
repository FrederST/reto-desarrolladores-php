<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShoppingCartItemController;
use App\Models\Product;
use App\ViewModels\Product\IndexViewModel;
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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function (IndexViewModel $indexViewModel) {
    $products = Product::filter(request()->input('filter', []))->with('images')->paginate();
    //dd($products);
    return Inertia::render('Dashboard', $indexViewModel->collection($products));
})->name('dashboard');

Route::resource('customers', CustomerController::class)->except(['create', 'edit', 'show'])
->middleware(['auth:sanctum', 'verified', 'role:admin']);

Route::put('customers/disable/{customer}', [CustomerController::class, 'disable'])
->middleware(['auth:sanctum', 'verified', 'role:admin'])
->name('customers.disable');

Route::resource('products', ProductController::class)->except(['create', 'edit', 'show'])
->middleware(['auth:sanctum', 'verified', 'role:admin']);

Route::put('products/disable/{product}', [ProductController::class, 'disable'])
->middleware(['auth:sanctum', 'verified', 'role:admin'])
->name('products.disable');

Route::post('products/import', [ProductController::class, 'import'])
->name('products.import');

Route::group(['prefix' => 'productImages'], function () {
    Route::post('upload/{productId}', [ProductImageController::class, 'upload'])->name('products.images.upload');
    Route::delete('{productImage}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
});

Route::resource('shoppingCartItems', ShoppingCartItemController::class)->except(['create', 'edit', 'show'])
->middleware(['auth:sanctum', 'verified']);

Route::resource('orders', OrderController::class)->except(['edit'])
->middleware(['auth:sanctum', 'verified']);

Route::get('orders/retry/{order}', [OrderController::class, 'retryPayment'])
->middleware(['auth:sanctum', 'verified'])
->name('orders.retry');

Route::get('all/orders', [OrderController::class, 'all'])
->middleware(['auth:sanctum', 'verified', 'role:admin'])
->name('orders.all');

Route::resource('reports', ReportController::class)->except(['create', 'edit'])
->middleware(['auth:sanctum', 'verified', 'role:admin']);

Route::get('reports/download/{report}', [ReportController::class, 'download'])->name('reports.download');

Route::get('test', function () {
    //return sprintf('%010d', 1234567890);
    return uniqid();
});
