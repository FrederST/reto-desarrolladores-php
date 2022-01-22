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
        $order = Order::all();

        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne(array_keys($order[0]->getAttributes()));
        $csv->insertAll($order->toArray());

        $filePath = "products/export-{$this->report->id}-{$this->report->created_at->toDateString()}.csv";

        Storage::disk('reports')->put($filePath, $csv->__toString());

        $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FINISHED, 'Report Created Successfully', $filePath);

        $this->report->user->notify(new ReportStatusChange($this->report));
    }

    public function failed(Throwable $exception): void
    {
        $this->setReportStatusInfoAndPath(ReportStatus::STATUS_FINISHED, 'Report Fail ' . $exception->getMessage());
        $this->report->refresh();
        $this->report->user->notify(new ReportStatusChange($this->report));
    }

    private function setReportStatusInfoAndPath(string $reportStatus, string $info, string $filePath = ''): void
    {
        $this->report->update([
            'status' => $reportStatus,
            'path' => "reports/{$filePath}",
            'info' => $info,
        ]);
    }
}
