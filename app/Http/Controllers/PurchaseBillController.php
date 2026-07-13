<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseBill;
use App\Models\Firm;
use App\Models\Party;

class PurchaseBillController extends Controller
{
    public function index()
    {
        $purchaseBills = PurchaseBill::with(['firm', 'party'])->latest('bill_date')->get();
        return view('purchase-bills-index', compact('purchaseBills'));
    }

    public function create()
    {
        $firms = Firm::orderBy('name')->get();
        $parties = Party::orderBy('name')->get();
        return view('purchase-bill', compact('firms', 'parties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'amount_without_gst' => 'nullable|numeric',
            'gst_percent' => 'nullable|numeric',
            'gst_rs' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'remark' => 'nullable|string',
        ]);

        PurchaseBill::create($validated);

        return redirect()->route('purchase-bill.index')->with('success', 'Purchase Bill created successfully.');
    }

    public function edit(PurchaseBill $purchaseBill)
    {
        $firms = Firm::orderBy('name')->get();
        $parties = Party::orderBy('name')->get();
        return view('purchase-bill', compact('purchaseBill', 'firms', 'parties'));
    }

    public function update(Request $request, PurchaseBill $purchaseBill)
    {
        $validated = $request->validate([
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'amount_without_gst' => 'nullable|numeric',
            'gst_percent' => 'nullable|numeric',
            'gst_rs' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'remark' => 'nullable|string',
        ]);

        $purchaseBill->update($validated);

        return redirect()->route('purchase-bill.index')->with('success', 'Purchase Bill updated successfully.');
    }

    public function destroy(PurchaseBill $purchaseBill)
    {
        $purchaseBill->delete();
        return redirect()->route('purchase-bill.index')->with('success', 'Purchase Bill deleted successfully.');
    }

    public function print(PurchaseBill $purchaseBill)
    {
        $purchaseBill->load(['firm', 'party']);
        return view('purchase-bill-print', compact('purchaseBill'));
    }
}
