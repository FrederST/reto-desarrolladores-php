<?php

namespace App\Reports;

use App\Constants\ReportStatus;
use App\Models\Product;
use App\Notifications\ReportStatusChange;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use SplTempFileObject;

class ProductsReport extends ReportBase
{
    public function handle(): void
    {
        $products = Product::all();

        $csv = Writer::createFromFileObject(new SplTempFileObject);
        $csv->insertOne(array_keys($products[0]->getAttributes()));
        $csv->insertAll($products->toArray());

        $filePath = "products/export-{$this->report->id}-{$this->report->created_at->toDateString()}.csv";

        Storage::disk('reports')->put($filePath, $csv->__toString());

        $this->report->update([
            'status' => ReportStatus::STATUS_FINISHED,
            'path' => "reports/{$filePath}",
        ]);
        $this->report->user->notify(new ReportStatusChange($this->report));
    }
}
