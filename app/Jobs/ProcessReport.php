<?php

namespace App\Jobs;

use App\Models\Report;
use App\Reports\ReportBase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private ReportBase $reportImpl;
    private Report $report;

    public function __construct(Report $report, ReportBase $reportImpl)
    {
        $this->reportImpl = $reportImpl;
        $this->report = $report;
    }

    public function handle(): void
    {
        $this->reportImpl->setReport($this->report)->handle();
    }

    public function failed(Throwable $exception): void
    {
        $this->reportImpl->failed($exception);
    }
}
