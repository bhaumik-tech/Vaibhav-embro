<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\OutputChalan;
use App\Models\OutputChalanItem;
use App\Models\Party;
use Illuminate\Http\Request;

class OutputChalanController extends Controller
{
    public function create()
    {
        return redirect()->route('register.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
        ]);

        $latestChalan = OutputChalan::latest('id')->first();
        $nextId = $latestChalan ? $latestChalan->id + 1 : 1;
        $chalanNo = $request->chalan_no ?? str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $outputChalan = OutputChalan::create([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
            'party_chalan_no' => $request->party_chalan_no,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
        ]);

        if (!empty($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['pcs'])) {
                    $outputChalan->items()->create([
                        'ch_no' => $item['ch_no'] ?? null,
                        'bundle' => $item['bundle'] ?? null,
                        'code' => $item['code'] ?? null,
                        'pcs' => $item['pcs'],
                        'rate' => $item['rate'] ?? 0,
                        'amount' => $item['amount'] ?? 0,
                    ]);
                }
            }
        } elseif (!empty($request->t_pcs) || !empty($request->t_rs)) {
            $outputChalan->items()->create([
                'pcs' => $request->t_pcs ?? 0,
                'amount' => $request->t_rs ?? 0,
                'rate' => 0,
            ]);
        }

        return redirect()->route('register.index')->with('success', 'Output Chalan saved successfully!');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
        ]);

        $latestChalan = OutputChalan::latest('id')->first();
        $nextId = $latestChalan ? $latestChalan->id + 1 : 1;
        $chalanNo = $request->chalan_no ?? str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $outputChalan = OutputChalan::create([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $chalanNo,
            'party_chalan_no' => $request->party_ch,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
        ]);

        if (!empty($request->t_pcs) || !empty($request->t_rs)) {
            $outputChalan->items()->create([
                'pcs' => $request->t_pcs ?? 0,
                'amount' => $request->t_rs ?? 0,
                'rate' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Output Chalan added via Quick Entry!');
    }

    public function edit(OutputChalan $outputChalan)
    {
        return redirect()->route('register.index');
    }

    public function update(Request $request, OutputChalan $outputChalan)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
        ]);

        $outputChalan->update([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'chalan_no' => $request->chalan_no ?? $outputChalan->chalan_no,
            'party_chalan_no' => $request->party_chalan_no,
            'gst' => $request->gst,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
        ]);

        $outputChalan->items()->delete();

        if (!empty($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['pcs'])) {
                    $outputChalan->items()->create([
                        'ch_no' => $item['ch_no'] ?? null,
                        'bundle' => $item['bundle'] ?? null,
                        'code' => $item['code'] ?? null,
                        'pcs' => $item['pcs'],
                        'rate' => $item['rate'] ?? 0,
                        'amount' => $item['amount'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('register.index')->with('success', 'Output Chalan updated successfully!');
    }

    public function destroy(OutputChalan $outputChalan)
    {
        $outputChalan->delete();

        return redirect()->route('register.index')->with('success', 'Output Chalan deleted successfully!');
    }
}
