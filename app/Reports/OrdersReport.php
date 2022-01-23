<?php

namespace App\Reports;

use App\Constants\ReportStatus;
use App\Models\Order;
use App\Notifications\ReportStatusChange;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use SplTempFileObject;
use Throwable;

class OrdersReport extends ReportBase
{
    public function handle(): void
    {
        $this->report->update([
            'status' => ReportStatus::STATUS_IN_PROCESS,
        ]);
        $orders = Order::filter($this->report->filters);

        $csv = Writer::createFromFileObject(new SplTempFileObject());

        if ($orders->count() == 0) {
            $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FINISHED, 'Not Found Orders');
            $this->notify();
            return;
        }
        $csv->insertOne(array_keys($orders[0]->getAttributes()));
        $csv->insertAll($orders->toArray());

        $filePath = "products/export-{$this->report->id}-{$this->report->created_at->toDateString()}.csv";

        Storage::disk('reports')->put($filePath, $csv->__toString());

        $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FINISHED, 'Report Created Successfully', $filePath);

        $this->notify();
    }

    public function failed(Throwable $exception): void
    {
        $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FAIL, 'Report Fail ' . $exception->getMessage());
        $this->notify();
    }

    private function setReportStatusInfoAndPath(string $reportStatus, string $info, string $filePath = ''): void
    {
        $this->report->update([
            'status' => $reportStatus,
            'path' => $filePath ? "reports/{$filePath}": null,
            'info' => $info,
        ]);
    }

    private function notify(): void
    {
        $this->report->refresh();
        $this->report->user->notify(new ReportStatusChange($this->report));
    }
}
