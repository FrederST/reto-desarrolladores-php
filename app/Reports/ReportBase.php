<?php

namespace App\Reports;

use App\Models\Report as ReportModel;
use App\Reports\Contracts\ReportContract;
use Throwable;

abstract class ReportBase implements ReportContract
{
    protected ReportModel $report;

    abstract public function handle(): void;

    abstract public function failed(Throwable $exception): void;

    public function setReport(ReportModel $report): self
    {
        $this->report = $report;
        return $this;
    }
}
