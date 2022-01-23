<?php

namespace App\Http\Controllers;

use App\Constants\ReportStatus;
use App\Http\Requests\Report\StoreRequest;
use App\Jobs\ProcessReport;
use App\Models\Report;
use App\Reports\ReportBase;
use App\ViewModels\Report\IndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public const REPORT_INDEX = 'reports.index';

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
            'info' => 'We notify when report is ready',
            'filters' => $request->input('filter', []),
            'user_id' => auth()->user()->id,
        ]);

        ProcessReport::dispatch($report, $reportImpl);
        return Redirect::route(self::REPORT_INDEX);
    }

    public function show(Report $report): Response
    {
        return Inertia::render('Report/Show', ['report' => $report]);
    }

    public function destroy(Report $report): RedirectResponse
    {
        Storage::delete($report->path);
        $report->delete();
        return Redirect::route(self::REPORT_INDEX);
    }

    public function download(Report $report): StreamedResponse
    {
        return Storage::download($report->path);
    }
}
