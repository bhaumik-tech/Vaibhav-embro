<?php

namespace App\Http\Controllers;

use App\Models\GenerateBill;
use App\Models\Firm;
use App\Models\Party;
use Illuminate\Http\Request;

class GenerateBillController extends Controller
{
    public function index()
    {
        $parties = Party::orderBy('name')->get();
        $query = GenerateBill::with(['items', 'firm', 'party']);
        if (request('party_id')) {
            $query->where('party_id', request('party_id'));
        }
        $generateBills = $query->latest('date')->get();
        $firms = Firm::getPermitted();

        return view('generate-bills-index', compact('parties', 'generateBills', 'firms'));
    }

    public function create()
    {
        $firms = Firm::getPermitted();
        $parties = Party::orderBy('name')->get();
        return view('generate-bill', compact('firms', 'parties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'items' => 'required|array',
        ]);

        $latestBill = GenerateBill::latest('id')->first();
        $nextId = $latestBill ? $latestBill->id + 1 : 1;
        $billNo = $request->bill_no ?? str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $generateBill = GenerateBill::create([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'bill_no' => $billNo,
            'date' => $request->date,
            'name' => $request->name,
            'add' => $request->add,
            'gst' => $request->gst,
            'vatav_percent' => $request->vatav_percent ?? 5.00,
            'sgst_percent' => $request->sgst_percent ?? 2.50,
            'cgst_percent' => $request->cgst_percent ?? 2.50,
            'tds_percent' => $request->tds_percent ?? 1.00,
        ]);

        foreach ($request->items as $item) {
            if (!empty($item['ch_no']) || !empty($item['pcs'])) {
                $generateBill->items()->create([
                    'ch_no' => $item['ch_no'] ?? null,
                    'details' => $item['details'] ?? [],
                    'pcs' => $item['pcs'] ?? 0,
                    'rate' => $item['rate'] ?? 0,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }

        if ($request->has('print')) {
            return redirect()->route('generate-bills.print', $generateBill->id);
        }

        return redirect()->route('generate-bills.index')->with('success', 'Generate Bill saved successfully!');
    }

    public function edit(GenerateBill $generateBill)
    {
        $firms = Firm::getPermitted();
        $parties = Party::orderBy('name')->get();
        $generateBill->load('items');
        return view('generate-bill-edit', compact('generateBill', 'firms', 'parties'));
    }

    public function update(Request $request, GenerateBill $generateBill)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'items' => 'required|array',
        ]);

        $generateBill->update([
            'firm_id' => $request->firm_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'bill_no' => $request->bill_no ?? $generateBill->bill_no,
            'name' => $request->name,
            'add' => $request->add,
            'gst' => $request->gst,
            'vatav_percent' => $request->vatav_percent ?? $generateBill->vatav_percent,
            'sgst_percent' => $request->sgst_percent ?? $generateBill->sgst_percent,
            'cgst_percent' => $request->cgst_percent ?? $generateBill->cgst_percent,
            'tds_percent' => $request->tds_percent ?? $generateBill->tds_percent,
        ]);

        $generateBill->items()->delete();

        foreach ($request->items as $item) {
            if (!empty($item['ch_no']) || !empty($item['pcs'])) {
                $generateBill->items()->create([
                    'ch_no' => $item['ch_no'] ?? null,
                    'details' => $item['details'] ?? [],
                    'pcs' => $item['pcs'] ?? 0,
                    'rate' => $item['rate'] ?? 0,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }

        if ($request->has('print')) {
            return redirect()->route('generate-bills.print', $generateBill->id);
        }

        return redirect()->route('generate-bills.index')->with('success', 'Generate Bill updated successfully!');
    }

    public function destroy(GenerateBill $generateBill)
    {
        $generateBill->delete();
        return redirect()->route('generate-bills.index')->with('success', 'Generate Bill deleted successfully!');
    }

    public function print(GenerateBill $generateBill)
    {
        $generateBill->load(['items', 'firm', 'party']);
        return view('generate-bill-print', compact('generateBill'));
    }
}
