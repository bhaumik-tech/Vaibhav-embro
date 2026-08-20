<?php

namespace App\Http\Controllers;

use App\Models\DhagaCutting;
use App\Models\DhCuttingPerson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DhagaCuttingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:dh_cutting,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:dh_cutting,remove', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $people = DhCuttingPerson::orderBy('person_name')->get();
        
        $selectedPersonId = $request->person_id ?? ($people->first()->id ?? null);
        $selectedPerson = $selectedPersonId ? DhCuttingPerson::find($selectedPersonId) : null;
        
        $selectedMonth = $request->month ?? date('m-Y'); // Format: MM-YYYY
        
        $parts = explode('-', $selectedMonth);
        $month = $parts[0] ?? date('m');
        $year = $parts[1] ?? date('Y');
        
        // Next/Prev months for the slider
        $currentDateObj = \Carbon\Carbon::createFromDate($year, $month, 1);
        
        $monthsList = [];
        // Generate a 4-month window (e.g. current, and 3 previous, or just a generic slider)
        // Mockup shows 04-2026, 05-2026, 06-2026, 07-2026. Let's provide an array of objects.
        for ($i = -2; $i <= 1; $i++) {
            $date = $currentDateObj->copy()->addMonths($i);
            $monthsList[] = [
                'label' => $date->format('m-Y'),
                'value' => $date->format('m-Y'),
                'is_current' => $i === 0
            ];
        }
        $prevMonth = $currentDateObj->copy()->subMonth()->format('m-Y');
        $nextMonth = $currentDateObj->copy()->addMonth()->format('m-Y');

        $aggregations = [];
        $totalWorkRs = 0;
        
        if ($selectedPersonId) {
            // Get all dhaga_cuttings for this person and month with items
            $cuttings = \App\Models\DhagaCutting::with('items')
                ->where('person_id', $selectedPersonId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->orderBy('date', 'asc')
                ->get();

            // Group items by rate_label manually to attach dates and parent IDs
            $rateGroups = [];
            foreach ($cuttings as $cutting) {
                foreach ($cutting->items as $item) {
                    if (!isset($rateGroups[$item->rate_label])) {
                        $rateGroups[$item->rate_label] = [
                            'total_pieces' => 0,
                            'total_rs' => 0,
                            'details' => []
                        ];
                    }
                    $rateGroups[$item->rate_label]['total_pieces'] += $item->pieces;
                    $rateGroups[$item->rate_label]['total_rs'] += $item->amount;
                    if ($item->pieces > 0) {
                        $rateGroups[$item->rate_label]['details'][] = [
                            'id' => $cutting->id,
                            'date' => \Carbon\Carbon::parse($cutting->date)->format('d/m/Y'),
                            'pieces' => $item->pieces,
                            'amount' => $item->amount,
                            'is_highlighted' => $cutting->is_highlighted
                        ];
                    }
                }
            }
            
            foreach ($rateGroups as $rateLabel => $data) {
                if ($data['total_pieces'] > 0) {
                    $aggregations[] = [
                        'rate_label' => $rateLabel,
                        'total_pieces' => $data['total_pieces'],
                        'total_rs' => $data['total_rs'],
                        'details' => $data['details']
                    ];
                    $totalWorkRs += $data['total_rs'];
                }
            }
            
            // Sort aggregations numerically by rate if possible, Custom at the end
            usort($aggregations, function ($a, $b) {
                if ($a['rate_label'] === 'Custom') return 1;
                if ($b['rate_label'] === 'Custom') return -1;
                return (float)$a['rate_label'] <=> (float)$b['rate_label'];
            });
        }

        $lastAddedDate = \App\Models\DhagaCutting::when($selectedPersonId, function($q) use ($selectedPersonId) {
            return $q->where('person_id', $selectedPersonId);
        })->max('date');
        $lastAddedDateFormatted = $lastAddedDate ? \Carbon\Carbon::parse($lastAddedDate)->format('d/m/Y') : 'None';

        return view('dhaga-cuttings.index', compact(
            'people', 'selectedPerson', 'selectedMonth', 'monthsList', 
            'prevMonth', 'nextMonth', 'aggregations', 'totalWorkRs',
            'month', 'year', 'lastAddedDateFormatted'
        ));
    }

    public function create()
    {
        $people = DhCuttingPerson::orderBy('person_name')->get();
        return view('dhaga-cuttings.create', compact('people'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:dh_cutting_people,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.rate_label' => 'required|string',
        ]);

        $dhagaCutting = \App\Models\DhagaCutting::create([
            'person_id' => $request->person_id,
            'date' => $request->date,
            'remark_note' => $request->remark_note,
            'is_highlighted' => $request->has('is_highlighted') ? 1 : 0,
            'total_pieces' => $request->total_pieces ?? 0,
            'total_amount' => $request->total_amount ?? 0,
        ]);

        foreach ($request->items as $item) {
            $dhagaCutting->items()->create([
                'rate_label' => $item['rate_label'],
                'rate_value' => $item['rate_value'] ?? 0,
                'pieces' => $item['pieces'] ?? 0,
                'amount' => $item['amount'] ?? 0,
            ]);
        }

        return redirect()->route('dhaga-cuttings.index', [
            'person_id' => $dhagaCutting->person_id,
            'month' => \Carbon\Carbon::parse($dhagaCutting->date)->format('m-Y')
        ])->with('success', 'Dhaga cutting entry saved successfully.');
    }

    public function edit(\App\Models\DhagaCutting $dhagaCutting)
    {
        $people = DhCuttingPerson::orderBy('person_name')->get();
        $dhagaCutting->load('items');
        
        // Map items by rate_label for easier form filling
        $itemsMap = $dhagaCutting->items->keyBy('rate_label')->toArray();
        
        return view('dhaga-cuttings.edit', compact('people', 'dhagaCutting', 'itemsMap'));
    }

    public function update(Request $request, \App\Models\DhagaCutting $dhagaCutting)
    {
        $request->validate([
            'person_id' => 'required|exists:dh_cutting_people,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.rate_label' => 'required|string',
        ]);

        $dhagaCutting->update([
            'person_id' => $request->person_id,
            'date' => $request->date,
            'remark_note' => $request->remark_note,
            'is_highlighted' => $request->has('is_highlighted') ? 1 : 0,
            'total_pieces' => $request->total_pieces ?? 0,
            'total_amount' => $request->total_amount ?? 0,
        ]);

        // Delete old items and recreate to easily handle changes
        $dhagaCutting->items()->delete();

        foreach ($request->items as $item) {
            $dhagaCutting->items()->create([
                'rate_label' => $item['rate_label'],
                'rate_value' => $item['rate_value'] ?? 0,
                'pieces' => $item['pieces'] ?? 0,
                'amount' => $item['amount'] ?? 0,
            ]);
        }

        return redirect()->route('dhaga-cuttings.index', [
            'person_id' => $dhagaCutting->person_id,
            'month' => \Carbon\Carbon::parse($dhagaCutting->date)->format('m-Y')
        ])->with('success', 'Dhaga cutting entry updated successfully.');
    }

    public function print(Request $request)
    {
        $people = DhCuttingPerson::orderBy('person_name')->get();
        
        $selectedPersonId = $request->person_id ?? ($people->first()->id ?? null);
        $selectedPerson = $selectedPersonId ? DhCuttingPerson::find($selectedPersonId) : null;
        
        $selectedMonth = $request->month ?? date('m-Y');
        
        $parts = explode('-', $selectedMonth);
        $month = $parts[0] ?? date('m');
        $year = $parts[1] ?? date('Y');
        
        $aggregations = [];
        $totalWorkRs = 0;
        
        if ($selectedPersonId) {
            $cuttings = \App\Models\DhagaCutting::with('items')
                ->where('person_id', $selectedPersonId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->orderBy('date', 'asc')
                ->get();

            $rateGroups = [];
            foreach ($cuttings as $cutting) {
                foreach ($cutting->items as $item) {
                    if (!isset($rateGroups[$item->rate_label])) {
                        $rateGroups[$item->rate_label] = [
                            'total_pieces' => 0,
                            'total_rs' => 0,
                            'details' => []
                        ];
                    }
                    $rateGroups[$item->rate_label]['total_pieces'] += $item->pieces;
                    $rateGroups[$item->rate_label]['total_rs'] += $item->amount;
                    if ($item->pieces > 0) {
                        $rateGroups[$item->rate_label]['details'][] = [
                            'id' => $cutting->id,
                            'date' => \Carbon\Carbon::parse($cutting->date)->format('d/m/Y'),
                            'pieces' => $item->pieces,
                            'amount' => $item->amount,
                            'is_highlighted' => $cutting->is_highlighted
                        ];
                    }
                }
            }
            
            foreach ($rateGroups as $rateLabel => $data) {
                if ($data['total_pieces'] > 0) {
                    $aggregations[] = [
                        'rate_label' => $rateLabel,
                        'total_pieces' => $data['total_pieces'],
                        'total_rs' => $data['total_rs'],
                        'details' => $data['details']
                    ];
                    $totalWorkRs += $data['total_rs'];
                }
            }
            
            usort($aggregations, function ($a, $b) {
                if ($a['rate_label'] === 'Custom') return 1;
                if ($b['rate_label'] === 'Custom') return -1;
                return (float)$a['rate_label'] <=> (float)$b['rate_label'];
            });
        }

        if (!$selectedPerson) {
            return back()->with('error', 'Person not found for printing.');
        }

        return view('dhaga-cuttings.print', compact(
            'selectedPerson', 'selectedMonth', 'aggregations', 'totalWorkRs', 'month', 'year'
        ));
    }
}
