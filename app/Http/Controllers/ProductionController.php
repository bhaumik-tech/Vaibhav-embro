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

        $aggregations = [];
        $totalPagar = 0;
        $totalBonus = 0;
        $totalUpad = 0;
        $totalRs = 0;

        if ($selectedKarigar) {
            for ($i = 1; $i <= 3; $i++) {
                $label = $i == 1 ? '1st machine' : ($i == 2 ? '2nd machine' : '3rd machine');
                $aggregations[$i-1] = [
                    'machine_label' => $label,
                    'total_hajri' => 0,
                    'total_work' => 0,
                    'pagar' => 0,
                    'bonus' => 0,
                    'details' => []
                ];
            }

            $productions = \App\Models\Production::with('details')->where('karigar_id', $selectedKarigar->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->orderBy('date', 'asc')
                ->get();
            
            foreach ($productions as $prod) {
                foreach ($prod->details as $detail) {
                    $idx = intval($detail->machine_index) - 1;
                    if (!isset($aggregations[$idx])) {
                        $aggregations[$idx] = [
                            'machine_label' => $detail->machine_index . 'th machine',
                            'total_hajri' => 0,
                            'total_work' => 0,
                            'pagar' => 0,
                            'bonus' => 0,
                            'details' => []
                        ];
                    }

                    $work = floatval($detail->top_production);
                    $pagar = floatval($detail->top_amount) + floatval($detail->dup_amount);
                    $bonus = floatval($detail->top_bonus) + floatval($detail->dup_bonus);
                    $hajri = $detail->is_half ? 0.5 : 1;
                    if ($idx > 0 && !$detail->is_active) {
                        $hajri = 0;
                        $work = 0;
                        $pagar = 0;
                        $bonus = 0;
                    }

                    $aggregations[$idx]['total_hajri'] += $hajri;
                    $aggregations[$idx]['total_work'] += $work;
                    $aggregations[$idx]['pagar'] += $pagar;
                    $aggregations[$idx]['bonus'] += $bonus;

                    $aggregations[$idx]['details'][] = [
                        'id' => $prod->id,
                        'date' => \Carbon\Carbon::parse($prod->date)->format('d/m'),
                        'hajri' => $hajri,
                        'work' => $work,
                        'pagar' => $pagar,
                        'bonus' => $bonus
                    ];

                    $totalPagar += $pagar;
                    $totalBonus += $bonus;
                }
            }

            $totalRs = $totalPagar + $totalBonus - $totalUpad;
        } else {
            $aggregations = [
                ['machine_label' => '1st machine', 'total_hajri' => 0, 'total_work' => 0, 'pagar' => 0, 'bonus' => 0, 'details' => []],
                ['machine_label' => '2nd machine', 'total_hajri' => 0, 'total_work' => 0, 'pagar' => 0, 'bonus' => 0, 'details' => []],
                ['machine_label' => '3rd machine', 'total_hajri' => 0, 'total_work' => 0, 'pagar' => 0, 'bonus' => 0, 'details' => []],
            ];
        }

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
        $request->validate([
            'karigar_id' => 'required|exists:karigars,id',
            'date' => 'required|date',
        ]);

        \DB::transaction(function () use ($request) {
            $production = \App\Models\Production::updateOrCreate(
                [
                    'karigar_id' => $request->karigar_id,
                    'date' => $request->date,
                ],
                [
                    'remark' => $request->remark,
                    'is_highlight' => $request->has('is_highlight'),
                ]
            );

            // Clear old details to cleanly insert updated ones
            $production->details()->delete();

            $totalAmount = 0;
            $totalBonus = 0;

            // Helper to save details
            $saveDetails = function($prefix, $index, $isDynamic = false) use ($request, $production, &$totalAmount, &$totalBonus) {
                $machineId = $isDynamic ? ($request->m_id[$index] ?? null) : $request->input("machine_{$index}_id");
                
                if (!$machineId) return;

                $type = $isDynamic ? ($request->m_type[$index] ?? null) : $request->input("m{$index}_type");
                
                // Top Fields
                $topProduction = $isDynamic ? ($request->m_production[$index] ?? null) : $request->input("m{$index}_production");
                $topAmount = $isDynamic ? ($request->m_amount[$index] ?? null) : $request->input("m{$index}_amount");
                $topBonus = $isDynamic ? ($request->m_bonus[$index] ?? null) : $request->input("m{$index}_bonus");
                
                // Dup Fields
                $dupAmount = $isDynamic ? ($request->dup_m_amount[$index] ?? null) : $request->input("dup_{$prefix}_amount");
                $dupBonus = $isDynamic ? ($request->dup_m_bonus[$index] ?? null) : $request->input("dup_{$prefix}_bonus");

                // Check if any data exists to save
                if ($topProduction || $topAmount || $dupAmount) {
                    $production->details()->create([
                        'machine_id' => $machineId,
                        'machine_index' => (string)($isDynamic ? ($index + 4) : $index),
                        'is_active' => $isDynamic ? isset($request->m_active[$index]) : $request->has("m{$index}_active"),
                        'is_half' => $isDynamic ? isset($request->m_half[$index]) : $request->has("m{$index}_half"),
                        'second_karigar_id' => $isDynamic ? ($request->m_second_karigar[$index] ?? null) : $request->input("m{$index}_second_karigar"),
                        'holiday_reason' => $isDynamic ? null : $request->input("m{$index}_holiday"),
                        'mate_type' => $type,
                        
                        'top_production' => $topProduction,
                        'top_amount' => $topAmount,
                        'top_bonus' => $topBonus,
                        
                        'dup_amount' => $dupAmount,
                        'dup_bonus' => $dupBonus,
                    ]);

                    $totalAmount += floatval($topAmount) + floatval($dupAmount);
                    $totalBonus += floatval($topBonus) + floatval($dupBonus);
                }
            };

            $saveDetails('m1', 1);
            $saveDetails('m2', 2);
            $saveDetails('m3', 3);

            // Dynamic machines
            if ($request->has('m_id') && is_array($request->m_id)) {
                foreach ($request->m_id as $i => $mId) {
                    $saveDetails('m', $i, true);
                }
            }

            $production->update([
                'total_amount' => $totalAmount,
                'total_bonus' => $totalBonus,
            ]);
        });

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Auto-saved']);
        }

        return back()->with('success', 'Production entry saved successfully!');
    }
    public function edit(\App\Models\Production $production)
    {
        // TODO: Implement edit view
        return back()->with('success', 'Edit functionality will be implemented soon.');
    }

    public function destroy(\App\Models\Production $production)
    {
        $production->delete();
        return back()->with('success', 'Production entry deleted successfully!');
    }
}
