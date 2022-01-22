<?php

namespace App\Http\Controllers;

use App\Actions\Product\UpdateAction;
use App\Constants\ReportStatus;
use App\Events\ProductCreatedOrUpdated;
use App\Http\Requests\Product\ImportRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Requests\Report\StoreRequest;
use App\Jobs\ImportProducts;
use App\Jobs\ProcessReport;
use App\Models\Product;
use App\Models\Report;
use App\Reports\ReportBase;
use App\ViewModels\Report\IndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public const PRODUCT_INDEX = 'reports.index';

    public function index(IndexViewModel $indexViewModel): Response
    {
        $reports = Report::paginate();
        return Inertia::render('Report/Index', $indexViewModel->collection($reports));
    }

    public function store(StoreRequest $request, ReportBase $reportImpl): RedirectResponse
    {
        $report = Report::create([
            'status' => ReportStatus::STATUS_CREATED,
            'type' => $request->input('type'),
            'user_id' => auth()->user()->id,
        ]);

        ProcessReport::dispatch($report, $reportImpl);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function update(UpdateRequest $request, Product $product, UpdateAction $updateAction): RedirectResponse
    {
        $product = $updateAction->execute($request->validated(), $product);
        ProductCreatedOrUpdated::dispatch($product, 'Product Updated');
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images() as $image) {
            Storage::delete($image->path);
            $image->delete();
        }
        $product->delete();
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function disable(Product $product): RedirectResponse
    {
        $product->update(['disabled_at' => now()]);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function import(ImportRequest $importRequest): RedirectResponse
    {
        $path = $importRequest->file('products')->storeAs('imports/products', uniqid() . '.csv');
        ImportProducts::dispatch($path, auth()->user()->id);
        return Redirect::route(self::PRODUCT_INDEX);
    }
}
