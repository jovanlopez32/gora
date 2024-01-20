<?php

namespace App\Http\Controllers;

use App\Models\CountReport;
use App\Models\Device;
use App\Models\ProblemReport;
use App\Models\Sector;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    //Nuevo método
    public function searchReports(Request $request)
    {
        $month_request = $request->month == null ? null : $request->month;
        $year_request = $request->year == null ? null : $request->year;
        $sector_request = $request->sector == null ? null : $request->sector;

        $request->session()->put('report_month', $month_request);
        $request->session()->put('report_year', $year_request);
        $request->session()->put('report_sector', $sector_request);

        return to_route('gora.reports.countersaltern');

    }

    //Nuevo método
    public function countReportAltern(Request $request)
    {
        $reportFilters = [
            'month' => $request->session()->get('report_month') == null ? null : $request->session()->get('report_month'),
            'year' => $request->session()->get('report_year') == null ? null : $request->session()->get('report_year'),
            'sector' => $request->session()->get('report_sector') == null ? null : $request->session()->get('report_sector'),
        ];

        //si los filtros están vacíos
        if($reportFilters['month'] == null && $reportFilters['year'] == null && $reportFilters['sector'] == null)
        {
            //Obtener el primer sector
            $sector = Sector::first();

            $tags = Tag::with([
                'countreports' => function ($query) {
                    $query->whereMonth('created_at', now()->month);
                },
                'countreports.sector',
                'countreports.device',
            ])
            ->with('device')
            ->where('sector_id', $sector->id)
            ->get();

            $suma_total_globales = 0;

            Carbon::setLocale('es_ES');
            $mes = Carbon::now()->format('F');

            $current_year = Carbon::now()->format('Y');
        }
        else
            //si las variables reciben año, mes y sector
            if($reportFilters['month'] != null && $reportFilters['year'] != null && $reportFilters['sector'] != null)
            {
                $tags = Tag::with([
                    'countreports' => function ($query) use ($reportFilters) {
                        if ($reportFilters['month']) {
                            $query->whereMonth('created_at', $reportFilters['month']);
                        }
                        if ($reportFilters['year']) {
                            $query->whereYear('created_at', $reportFilters['year']);
                        }
                    },
                    'countreports.sector',
                    'countreports.device',
                ])
                ->with('device')
                ->where('sector_id', $reportFilters['sector'])
                ->get();


                $sector = Sector::find($reportFilters['sector']);

                $suma_total_globales = $tags->flatMap(function ($tag) {
                    return $tag->countreports->pluck('total_global');
                })->sum();

                Carbon::setLocale('es_ES');
                $mes = $reportFilters['month'] ? Carbon::create()->month($reportFilters['month'])->format('F') : null;

                $current_year = $reportFilters['year'];
            }

        $year = Carbon::now()->year;
        // Crear un arreglo con los próximos tres años
        $years = [];
        for ($i = -1; $i < 2; $i++) {
            $years[] = $year + $i;
        }

        $tagsReport = Tag::with(['countreports', 'countreports.sector', 'countreports.device'])->get();
        $sectors = Sector::all();

        return Inertia::render('Report/CounterReportAltern', compact('sectors', 'tagsReport', 'tags', 'mes', 'years', 'current_year', 'suma_total_globales', 'sector'));
    }

    //Nuevo método
    public function cleanSearchReports(Request $request)
    {
        $request->session()->put('report_month', null);
        $request->session()->put('report_year', null);
        $request->session()->put('report_sector', null);

        return to_route('gora.reports.countersaltern');
    }

    public function countReport() {

        $tagsReport = Tag::with(['countreports', 'countreports.sector', 'countreports.device'])->get();
        $sectors = Sector::all();

        return Inertia::render('Report/CounterReport', compact('tagsReport', 'sectors'));
    }

    public function problemReport() {

        $problems_report = ProblemReport::with('tag')->with('sector')->with('device')->orderBy('created_at', 'desc')->paginate(100);

        return Inertia::render('Report/ProblemReport', compact('problems_report'));
    }
}
