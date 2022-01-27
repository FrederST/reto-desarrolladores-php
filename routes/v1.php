<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class)
->middleware(['auth:sanctum']);

Route::put('products/disable/{product}', [ProductController::class, 'disable'])
->middleware(['auth:sanctum'])
->name('products.disable');

Route::post('products/import', [ProductController::class, 'import'])
->middleware(['auth:sanctum'])
->name('products.import');
