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

        $chalanNo = $request->chalan_no;
        if (!$chalanNo) {
            $latestChalan = InputChalan::where('party_id', $request->party_id)
                ->where('firm_id', $request->firm_id)
                ->orderByRaw('CAST(chalan_no AS UNSIGNED) DESC')
                ->first();
            $nextId = $latestChalan ? (intval($latestChalan->chalan_no) + 1) : 1;
            $chalanNo = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }

        if (InputChalan::where('party_id', $request->party_id)
            ->where('firm_id', $request->firm_id)
            ->where('chalan_no', $chalanNo)
            ->exists()) {
            return back()->with('error', 'Duplicate Entry: Chalan No ' . $chalanNo . ' already exists for this Party and Firm!')->withInput();
        }

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

        $chalanNo = $request->chalan_no;
        if (!$chalanNo) {
            $latestChalan = InputChalan::where('party_id', $request->party_id)
                ->where('firm_id', $request->firm_id)
                ->orderByRaw('CAST(chalan_no AS UNSIGNED) DESC')
                ->first();
            $nextId = $latestChalan ? (intval($latestChalan->chalan_no) + 1) : 1;
            $chalanNo = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }

        if (InputChalan::where('party_id', $request->party_id)
            ->where('firm_id', $request->firm_id)
            ->where('chalan_no', $chalanNo)
            ->exists()) {
            return back()->with('error', 'Duplicate Entry: Chalan No ' . $chalanNo . ' already exists for this Party and Firm!')->withInput();
        }

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
        $parties = \App\Models\Party::getPermitted()->load('firms');
        $dropdownOptions = \App\Models\DropdownOption::all()->groupBy('column_name');
        $inputChalan->load('items');
        return view('input-chalan-edit', compact('inputChalan', 'firms', 'parties', 'dropdownOptions'));
    }

    public function update(Request $request, InputChalan $inputChalan)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'firm_id' => 'required|exists:firms,id',
            'date' => 'required|date',
            'items' => 'required|array',
        ]);

        $chalanNo = $request->chalan_no ?? $inputChalan->chalan_no;
        
        if (InputChalan::where('party_id', $request->party_id)
            ->where('firm_id', $request->firm_id)
            ->where('chalan_no', $chalanNo)
            ->where('id', '!=', $inputChalan->id)
            ->exists()) {
            return back()->with('error', 'Duplicate Entry: Chalan No ' . $chalanNo . ' already exists for this Party and Firm!')->withInput();
        }

        $inputChalan->update([
            'party_id' => $request->party_id,
            'firm_id' => $request->firm_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
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

        if ($request->has('return_to')) {
            return redirect($request->return_to)->with('success', 'Input Chalan updated successfully!');
        }

        return redirect()->route('register.index')->with('success', 'Input Chalan updated successfully!');
    }

    public function destroy(InputChalan $inputChalan)
    {
        $inputChalan->delete();
        return redirect()->route('register.index')->with('success', 'Input Chalan deleted successfully!');
    }
}
