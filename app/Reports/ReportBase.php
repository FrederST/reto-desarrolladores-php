<?php

namespace App\Reports;

use App\Models\Report as ReportModel;
use App\Reports\Contracts\ReportContract;

abstract class ReportBase implements ReportContract
{
    protected ReportModel $report;

    abstract public function handle(): void;

    public function setReport(ReportModel $report): self
    {
        $this->report = $report;
        return $this;
    }
}
