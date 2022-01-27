<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\ViewModels\Product\IndexViewModel;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public const PRODUCT_INDEX = 'products.index';

    public function index(IndexViewModel $indexViewModel): Response
    {
        $products = Product::filter(FilterHelper::removeNullValues(request()->input('filter', [])))
        ->where('quantity', '>', '0')
        ->whereNull('disabled_at')
        ->with('images')->paginate();
        return Inertia::render('Dashboard', $indexViewModel->collection(ProductResource::collection($products)));
    }
}
