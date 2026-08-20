<?php

namespace App\Http\Controllers;

use App\Models\GenerateChalan;
use App\Models\Firm;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GenerateChalanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:generate_chalan,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:generate_chalan,remove', only: ['destroy']),
        ];
    }

    public function index()
    {
        $parties = Party::getPermitted();
        
        if (!request('party_id') && $parties->isNotEmpty()) {
            return redirect()->route('generate-chalans.index', array_merge(request()->query(), ['party_id' => $parties->first()->id]));
        }
        
        $query = GenerateChalan::with('items');
        if (request('party_id')) {
            $query->where('party_id', request('party_id'));
        }
        
        $firms = Firm::getPermitted();
        $permittedFirmIds = $firms->pluck('id')->toArray();
        if (!auth()->user()->isAdmin()) {
            $query->whereIn('firm_id', $permittedFirmIds);
        }
        
        $query->where('is_draft', false);
        
        if (request('filter_firm_id')) {
            $query->where('firm_id', request('filter_firm_id'));
        }
        
        if (request('filter_date_from') && request('filter_date_to')) {
            $query->whereBetween('date', [request('filter_date_from'), request('filter_date_to')]);
        } elseif (request('filter_date_from')) {
            $query->whereDate('date', '>=', request('filter_date_from'));
        } elseif (request('filter_date_to')) {
            $query->whereDate('date', '<=', request('filter_date_to'));
        }
        
        $generateChalans = $query->latest('date')->get();

        return view('generate-chalans-index', compact('parties', 'generateChalans', 'firms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'items' => 'required|array',
        ]);

        $maxNumeric = GenerateChalan::where('firm_id', $request->firm_id)
            ->where('party_id', $request->party_id)
            ->get()
            ->map(function ($chalan) {
                return (int) preg_replace('/[^0-9]/', '', $chalan->chalan_no);
            })
            ->max();
            
        $nextId = $maxNumeric ? $maxNumeric + 1 : 1;
        $chalanNo = $request->chalan_no ?? $nextId;

        $isDraft = $request->input('action') === 'draft' ? true : false;

        $generateChalan = GenerateChalan::create([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
            'is_draft' => $isDraft,
        ]);

        foreach ($request->items as $item) {
            if (!empty($item['pcs'])) {
                $generateChalan->items()->create([
                    'ch_no' => $item['ch_no'] ?? null,
                    'bundle' => $item['bundle'] ?? null,
                    'code' => $item['code'] ?? null,
                    'pcs' => $item['pcs'],
                    'rate' => $item['rate'] ?? 0,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }

        if ($request->has('print')) {
            return redirect()->route('generate-chalans.print', $generateChalan->id);
        }

        if ($isDraft) {
            return redirect()->route('generate-chalans.index')->with('success', 'Draft saved successfully! (Not visible in register)');
        }

        return redirect()->route('generate-chalans.index')->with('success', 'Generate Chalan saved successfully!');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
        ]);

        $maxNumeric = GenerateChalan::where('firm_id', $request->firm_id)
            ->where('party_id', $request->party_id)
            ->get()
            ->map(function ($chalan) {
                return (int) preg_replace('/[^0-9]/', '', $chalan->chalan_no);
            })
            ->max();
            
        $nextId = $maxNumeric ? $maxNumeric + 1 : 1;
        $chalanNo = $request->chalan_no ?? $nextId;

        $generateChalan = GenerateChalan::create([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
            'party_ch' => $request->party_ch,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
        ]);

        // If they provided pcs and rs, create a dummy item to hold totals
        if (!empty($request->t_pcs) || !empty($request->t_rs)) {
            $generateChalan->items()->create([
                'pcs' => $request->t_pcs ?? 0,
                'amount' => $request->t_rs ?? 0,
                'rate' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Output Chalan added via Quick Entry!');
    }

    public function edit(GenerateChalan $generateChalan)
    {
        $firms = Firm::getPermitted();
        $parties = Party::getPermitted();
        $generateChalan->load('items');
        return view('generate-chalan-edit', compact('generateChalan', 'firms', 'parties'));
    }

    public function update(Request $request, GenerateChalan $generateChalan)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'items' => 'required|array',
        ]);

        $isDraft = $request->input('action') === 'draft' ? true : false;

        $generateChalan->update([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $request->chalan_no ?? $generateChalan->chalan_no,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
            'is_draft' => $isDraft,
        ]);

        $generateChalan->items()->delete();

        foreach ($request->items as $item) {
            if (!empty($item['pcs'])) {
                $generateChalan->items()->create([
                    'ch_no' => $item['ch_no'] ?? null,
                    'bundle' => $item['bundle'] ?? null,
                    'code' => $item['code'] ?? null,
                    'pcs' => $item['pcs'],
                    'rate' => $item['rate'] ?? 0,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }

        if ($request->has('print')) {
            return redirect()->route('generate-chalans.print', $generateChalan->id);
        }

        if ($isDraft) {
            return redirect()->route('generate-chalans.index')->with('success', 'Draft updated successfully! (Not visible in register)');
        }

        if ($request->has('return_to')) {
            return redirect($request->return_to)->with('success', 'Generate Chalan updated successfully!');
        }

        return redirect()->route('generate-chalans.index')->with('success', 'Generate Chalan updated successfully!');
    }

    public function destroy(GenerateChalan $generateChalan)
    {
        $generateChalan->delete();
        return redirect()->route('generate-chalans.index')->with('success', 'Generate Chalan deleted successfully!');
    }

    public function print(Request $request, GenerateChalan $generateChalan)
    {
        $generateChalan->load(['items', 'firm', 'party']);
        $isPreview = $request->has('preview');
        return view('generate-chalan-print', compact('generateChalan', 'isPreview'));
    }

    public function printBulk(Request $request)
    {
        $chalanIds = $request->input('chalan_ids');
        if (empty($chalanIds)) {
            $chalanIds = [];
        } else {
            $chalanIds = explode(',', $chalanIds);
        }

        if (empty($chalanIds)) {
            return back()->with('error', 'No chalans selected for printing.');
        }

        $generateChalans = GenerateChalan::with(['items', 'firm', 'party'])
            ->whereIn('id', $chalanIds)
            ->get();

        return view('generate-chalan-print-bulk', compact('generateChalans'));
    }

    public function preview(Request $request)
    {
        try {
            $firm = Firm::find($request->firm_id);
            $party = Party::find($request->party_id);
            
            $generateChalan = new GenerateChalan([
                'firm_id' => $request->firm_id,
                'party_id' => $request->party_id,
                'date' => $request->date,
                'chalan_no' => $request->chalan_no ?? 'AUTO',
                'gst' => $request->gst,
                'payment_date' => $request->payment_date,
                'payment_detail' => $request->payment_detail,
            ]);

            $generateChalan->setRelation('firm', $firm);
            $generateChalan->setRelation('party', $party);

            $items = collect();
            if ($request->items) {
                foreach ($request->items as $item) {
                    if (!empty($item['pcs'])) {
                        $items->push(new \App\Models\GenerateChalanItem([
                            'ch_no' => $item['ch_no'] ?? null,
                            'bundle' => $item['bundle'] ?? null,
                            'code' => $item['code'] ?? null,
                            'pcs' => $item['pcs'],
                            'rate' => $item['rate'] ?? 0,
                            'amount' => $item['amount'] ?? 0,
                        ]));
                    }
                }
            }
            $generateChalan->setRelation('items', $items);
            $isPreview = true;

            return view('generate-chalan-print', compact('generateChalan', 'isPreview'));
        } catch (\Exception $e) {
            return response($e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile(), 500);
        }
    }
}
