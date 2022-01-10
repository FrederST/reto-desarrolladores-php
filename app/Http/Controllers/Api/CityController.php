<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Doctrine\Inflector\Rules\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Khsing\World\Models\City;

class CityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = City::where('country_id', $request->input('country_id', 187))->get();
        return response()->json($data);
    }
}
