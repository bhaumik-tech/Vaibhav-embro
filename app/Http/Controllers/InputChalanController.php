<?php

namespace App\Http\Controllers;

use App\Models\InputChalan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class InputChalanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:input_chalan,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:input_chalan,remove', only: ['destroy']),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'firm_id' => 'required|exists:firms,id',
            'date' => 'required|date',
            'items' => 'required|array',
        ]);

        $latestChalan = InputChalan::latest('id')->first();
        $nextId = $latestChalan ? $latestChalan->id + 1 : 1;
        $chalanNo = $request->chalan_no ?? str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $inputChalan = InputChalan::create([
            'party_id' => $request->party_id,
            'firm_id' => $request->firm_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
        ]);

        foreach ($request->items as $item) {
            // Only insert rows that have at least pcs filled (or whatever logic makes sense)
            if (!empty($item['pcs'])) {
                $inputChalan->items()->create([
                    'chart' => $item['chart'] ?? null,
                    'detail' => $item['detail'] ?? null,
                    'mtr' => $item['mtr'] ?? null,
                    'note' => $item['note'] ?? null,
                    'pcs' => $item['pcs'],
                    'bundles' => $item['bundles'] ?? null,
                ]);
            }
        }

        return redirect()->route('register.index')->with('success', 'Input Chalan saved successfully!');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'firm_id' => 'required|exists:firms,id',
            'date' => 'required|date',
            'pcs' => 'required|numeric'
        ]);

        $latestChalan = InputChalan::latest('id')->first();
        $nextId = $latestChalan ? $latestChalan->id + 1 : 1;
        $chalanNo = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $inputChalan = InputChalan::create([
            'party_id' => $request->party_id,
            'firm_id' => $request->firm_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
        ]);

        $inputChalan->items()->create([
            'chart' => $request->chart,
            'detail' => $request->detail,
            'mtr' => $request->mtr,
            'note' => $request->note,
            'pcs' => $request->pcs,
            'bundles' => $request->bundles,
        ]);

        return redirect()->back()->with('success', 'Quick entry added!');
    }

    public function edit(InputChalan $inputChalan)
    {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        $inputChalan->load('items');
        return view('input-chalan-edit', compact('inputChalan', 'firms', 'parties'));
    }

    public function update(Request $request, InputChalan $inputChalan)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'firm_id' => 'required|exists:firms,id',
            'date' => 'required|date',
            'items' => 'required|array',
        ]);

        $inputChalan->update([
            'party_id' => $request->party_id,
            'firm_id' => $request->firm_id,
            'date' => $request->date,
            'chalan_no' => $request->chalan_no ?? $inputChalan->chalan_no,
        ]);

        $inputChalan->items()->delete();

        foreach ($request->items as $item) {
            if (!empty($item['pcs'])) {
                $inputChalan->items()->create([
                    'chart' => $item['chart'] ?? null,
                    'detail' => $item['detail'] ?? null,
                    'mtr' => $item['mtr'] ?? null,
                    'note' => $item['note'] ?? null,
                    'pcs' => $item['pcs'],
                    'bundles' => $item['bundles'] ?? null,
                ]);
            }
        }

        return redirect()->route('register.index')->with('success', 'Input Chalan updated successfully!');
    }

    public function destroy(InputChalan $inputChalan)
    {
        $inputChalan->delete();
        return redirect()->route('register.index')->with('success', 'Input Chalan deleted successfully!');
    }
}
