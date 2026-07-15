<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $karigars = \App\Models\Karigar::orderBy('name')->get();
        
        $selectedKarigar = null;
        if ($request->karigar_id) {
            $selectedKarigar = \App\Models\Karigar::find($request->karigar_id);
        }

        $month = $request->month ? (int)$request->month : date('n');
        $year = $request->year ? (int)$request->year : date('Y');

        $currentDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        
        $prevMonthDate = $currentDate->copy()->subMonth();
        $prevMonth = $prevMonthDate->month;
        $prevYear = $prevMonthDate->year;
        
        $nextMonthDate = $currentDate->copy()->addMonth();
        $nextMonth = $nextMonthDate->month;
        $nextYear = $nextMonthDate->year;

        $monthsList = [];
        for ($i = -1; $i <= 2; $i++) {
            $date = $currentDate->copy()->addMonths($i);
            $monthsList[] = [
                'value' => $date->month,
                'year' => $date->year,
                'label' => $date->format('m-Y'),
                'is_current' => $i === 0
            ];
        }

        $aggregations = [
            [
                'machine_label' => '1st machine',
                'total_hajri' => 0,
                'total_work' => 0,
                'pagar' => 0,
                'bonus' => 0,
                'details' => []
            ],
            [
                'machine_label' => '2nd machine',
                'total_hajri' => 0,
                'total_work' => 0,
                'pagar' => 0,
                'bonus' => 0,
                'details' => []
            ],
            [
                'machine_label' => '3rd machine',
                'total_hajri' => 0,
                'total_work' => 0,
                'pagar' => 0,
                'bonus' => 0,
                'details' => []
            ]
        ];

        $totalPagar = 0;
        $totalBonus = 0;
        $totalUpad = 0;
        $totalRs = 0;

        return view('productions.index', compact(
            'karigars', 'selectedKarigar', 'month', 'year', 'monthsList', 
            'prevMonth', 'prevYear', 'nextMonth', 'nextYear',
            'aggregations', 'totalPagar', 'totalBonus', 'totalUpad', 'totalRs'
        ));
    }

    public function create()
    {
        $karigars = \App\Models\Karigar::with(['machine1', 'machine2', 'machine3'])->orderBy('name')->get();
        return view('productions.create', compact('karigars'));
    }

    public function store(Request $request)
    {
        // To be implemented fully based on logic
        return back()->with('success', 'Production entry saved successfully!');
    }
}
