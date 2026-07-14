<?php

namespace App\Http\Controllers;

use App\Models\GenerateChalan;
use App\Models\Firm;
use App\Models\Party;
use Illuminate\Http\Request;

class GenerateChalanController extends Controller
{
    public function index()
    {
        $parties = Party::orderBy('name')->get();
        $query = GenerateChalan::with('items');
        if (request('party_id')) {
            $query->where('party_id', request('party_id'));
        }
        $generateChalans = $query->latest('date')->get();
        $firms = Firm::getPermitted();

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

        $latestChalan = GenerateChalan::latest('id')->first();
        $nextId = $latestChalan ? $latestChalan->id + 1 : 1;
        $chalanNo = $request->chalan_no ?? str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $generateChalan = GenerateChalan::create([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
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

        return redirect()->route('generate-chalans.index')->with('success', 'Generate Chalan saved successfully!');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
        ]);

        $latestChalan = GenerateChalan::latest('id')->first();
        $nextId = $latestChalan ? $latestChalan->id + 1 : 1;
        $chalanNo = $request->chalan_no ?? str_pad($nextId, 4, '0', STR_PAD_LEFT);

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
        $parties = Party::orderBy('name')->get();
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

        $generateChalan->update([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $request->chalan_no ?? $generateChalan->chalan_no,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
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

        return redirect()->route('generate-chalans.index')->with('success', 'Generate Chalan updated successfully!');
    }

    public function destroy(GenerateChalan $generateChalan)
    {
        $generateChalan->delete();
        return redirect()->route('generate-chalans.index')->with('success', 'Generate Chalan deleted successfully!');
    }

    public function print(GenerateChalan $generateChalan)
    {
        $generateChalan->load(['items', 'firm', 'party']);
        return view('generate-chalan-print', compact('generateChalan'));
    }
}
