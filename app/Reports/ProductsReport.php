<?php

namespace App\Reports;

use App\Constants\ReportStatus;
use App\Models\Product;
use App\Notifications\ReportStatusChange;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use SplTempFileObject;
use Throwable;

class ProductsReport extends ReportBase
{
    public function handle(): void
    {
        $this->report->update([
            'status' => ReportStatus::STATUS_IN_PROCESS,
        ]);
        $products = Product::filter(['filter' => ['product_query' => $this->report->filters]])->get();

        if ($products->count() == 0) {
            $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FINISHED, 'Not Found Products');
            $this->notify();
            return;
        }

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne(array_keys($products[0]->getAttributes()));
        $csv->insertAll($products->toArray());

        $filePath = "products/export-{$this->report->id}-{$this->report->created_at->toDateString()}.csv";

        Storage::disk('reports')->put($filePath, $csv->__toString());

        $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FINISHED, 'Report Created Successfully', $filePath);

        $this->notify();
    }

    public function failed(Throwable $exception): void
    {
        $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FINISHED, 'Report Fail ' . $exception->getMessage());
        $this->notify();
    }

    private function setReportStatusInfoAndPath(string $reportStatus, string $info, string $filePath = ''): void
    {
        $this->report->update([
            'status' => $reportStatus,
            'path' => $filePath ? "reports/{$filePath}" : null,
            'info' => $info,
        ]);
    }

    private function notify(): void
    {
        $this->report->refresh();
        $this->report->user->notify(new ReportStatusChange($this->report));
    }
}
