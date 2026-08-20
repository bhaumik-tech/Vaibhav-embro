<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\PurchaseBill;
use App\Models\Firm;
use App\Models\ThreadBoxSetup;

class PurchaseBillController extends Controller implements HasMiddleware
{
    private function companyNamesList()
    {
        $threadCompanies = ThreadBoxSetup::select('company_name', 'gst_number as gst_no')
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->get();

        $purchaseBills = PurchaseBill::select('company_name', 'gst_no', 'cheque_no')
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->latest('id')
            ->get();

        $companies = collect();

        foreach ($purchaseBills as $bill) {
            if (!$companies->has($bill->company_name)) {
                $companies->put($bill->company_name, [
                    'name' => $bill->company_name,
                    'gst_no' => $bill->gst_no,
                    'cheque_no' => $bill->cheque_no,
                ]);
            }
        }

        foreach ($threadCompanies as $thread) {
            if (!$companies->has($thread->company_name)) {
                $companies->put($thread->company_name, [
                    'name' => $thread->company_name,
                    'gst_no' => $thread->gst_no,
                    'cheque_no' => null,
                ]);
            } else {
                $existing = $companies->get($thread->company_name);
                if (empty($existing['gst_no']) && !empty($thread->gst_no)) {
                    $existing['gst_no'] = $thread->gst_no;
                    $companies->put($thread->company_name, $existing);
                }
            }
        }

        return $companies->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:purchase_bill,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:purchase_bill,remove', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $firms = Firm::getPermitted();

        if (!$request->filled('firm_id') && $firms->isNotEmpty()) {
            return redirect()->route('purchase-bill.index', array_merge($request->query(), ['firm_id' => $firms->first()->id]));
        }

        $query = PurchaseBill::with(['firm']);

        if ($request->filled('firm_id')) {
            $query->where('firm_id', $request->firm_id);
        }

        $permittedFirmIds = $firms->pluck('id')->toArray();
        if (!auth()->user()->isAdmin()) {
            $query->whereIn('firm_id', $permittedFirmIds);
        }

        $purchaseBills = $query->latest('bill_date')->get();
        return view('purchase-bills-index', compact('purchaseBills', 'firms'));
    }

    public function create()
    {
        $firms = Firm::getPermitted();
        $companyNames = $this->companyNamesList();
        $recentBills = PurchaseBill::with(['firm'])->latest('id')->take(10)->get();
        return view('purchase-bill', compact('firms', 'companyNames', 'recentBills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'company_name' => 'required|string|max:255',
            'amount_without_gst' => 'nullable|numeric',
            'gst_percent' => 'nullable|numeric',
            'gst_rs' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'remark' => 'nullable|string',
            'gst_no' => 'nullable|string|max:255',
            'cheque_no' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['company_name'] = trim($validated['company_name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('purchase_bills', 'public');
        }

        PurchaseBill::create($validated);
        $threadBox = ThreadBoxSetup::firstOrCreate(['company_name' => $validated['company_name']]);
        if (!empty($validated['gst_no']) && empty($threadBox->gst_number)) {
            $threadBox->update(['gst_number' => $validated['gst_no']]);
        }

        return redirect()->route('purchase-bill.create')->with('success', 'Purchase Bill created successfully.');
    }

    public function edit(PurchaseBill $purchaseBill)
    {
        $firms = Firm::getPermitted();
        $companyNames = $this->companyNamesList();
        $recentBills = PurchaseBill::with(['firm'])->latest('id')->take(10)->get();
        return view('purchase-bill', compact('purchaseBill', 'firms', 'companyNames', 'recentBills'));
    }

    public function update(Request $request, PurchaseBill $purchaseBill)
    {
        $validated = $request->validate([
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'company_name' => 'required|string|max:255',
            'amount_without_gst' => 'nullable|numeric',
            'gst_percent' => 'nullable|numeric',
            'gst_rs' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'remark' => 'nullable|string',
            'gst_no' => 'nullable|string|max:255',
            'cheque_no' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['company_name'] = trim($validated['company_name']);

        if ($request->hasFile('image')) {
            if ($purchaseBill->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($purchaseBill->image);
            }
            $validated['image'] = $request->file('image')->store('purchase_bills', 'public');
        }

        $purchaseBill->update($validated);
        $threadBox = ThreadBoxSetup::firstOrCreate(['company_name' => $validated['company_name']]);
        if (!empty($validated['gst_no']) && empty($threadBox->gst_number)) {
            $threadBox->update(['gst_number' => $validated['gst_no']]);
        }

        return redirect()->route('purchase-bill.index')->with('success', 'Purchase Bill updated successfully.');
    }

    public function show(PurchaseBill $purchaseBill)
    {
        $purchaseBill->load(['firm']);
        return view('purchase-bill-show', compact('purchaseBill'));
    }

    public function destroy(PurchaseBill $purchaseBill)
    {
        if ($purchaseBill->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($purchaseBill->image);
        }
        $purchaseBill->delete();
        return back()->with('success', 'Purchase Bill deleted successfully.');
    }

    public function print(PurchaseBill $purchaseBill)
    {
        $purchaseBill->load(['firm']);
        return view('purchase-bill-print', compact('purchaseBill'));
    }
}
