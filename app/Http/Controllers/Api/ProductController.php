<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $data = Product::where('name', 'LIKE','%'.$request->searchTerm.'%')->get();
        return response()->json($data);
    }
}
