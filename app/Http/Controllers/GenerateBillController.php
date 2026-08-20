<?php

namespace App\Http\Controllers;

use App\Models\GenerateBill;
use App\Models\Firm;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GenerateBillController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:generate_bill,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:generate_bill,remove', only: ['destroy']),
        ];
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $parties = Party::getPermitted();
        
        if (!$request->filled('party_id') && $parties->isNotEmpty()) {
            return redirect()->route('generate-bills.index', array_merge($request->query(), ['party_id' => $parties->first()->id]));
        }

        $query = GenerateBill::with(['items', 'firm', 'party'])->where('is_draft', false);
        
        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }
        
        if ($request->filled('filter_firm_id')) {
            $query->where('firm_id', $request->filter_firm_id);
        }

        if ($request->filled('filter_date_from') && $request->filled('filter_date_to')) {
            $query->whereBetween('date', [$request->filter_date_from, $request->filter_date_to]);
        } elseif ($request->filled('filter_date_from')) {
            $query->whereDate('date', '>=', $request->filter_date_from);
        } elseif ($request->filled('filter_date_to')) {
            $query->whereDate('date', '<=', $request->filter_date_to);
        }

        if ($request->filled('search')) {
            $query->where('bill_no', 'like', '%' . $request->search . '%');
        }
        
        $firms = Firm::getPermitted();
        $permittedFirmIds = $firms->pluck('id')->toArray();
        if (!auth()->user()->isAdmin()) {
            $query->whereIn('firm_id', $permittedFirmIds);
        }
        
        $generateBills = $query->latest('date')->get()->sortBy(function($item) {
            return (int) preg_replace('/[^0-9]/', '', $item->bill_no);
        })->values();
        
        $page = request()->get('page', 1);
        $perPage = 15;
        $generateBills = new \Illuminate\Pagination\LengthAwarePaginator(
            $generateBills->forPage($page, $perPage),
            $generateBills->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $firms = Firm::getPermitted();

        $firmIds = collect($generateBills->items())->pluck('firm_id')->unique()->toArray();
        $partyIds = collect($generateBills->items())->pluck('party_id')->unique()->toArray();
        
        $rcvdPayments = \App\Models\RcvdPayment::whereNotNull('bill_no')
            ->whereIn('firm_id', $firmIds)
            ->whereIn('party_id', $partyIds)
            ->get();
        
        foreach ($generateBills->items() as $bill) {
            $payment = $rcvdPayments->first(function ($p) use ($bill) {
                if ($p->firm_id != $bill->firm_id || $p->party_id != $bill->party_id) return false;
                $pBills = array_map('trim', explode(',', $p->bill_no));
                return in_array((string)$bill->bill_no, $pBills, true);
            });
            if (!$payment) {
                $billChNos = $bill->items->pluck('ch_no')->filter()->map(fn($v) => (string)$v)->toArray();
                if (!empty($billChNos)) {
                    $payment = $rcvdPayments->first(function ($p) use ($bill, $billChNos) {
                        if ($p->firm_id != $bill->firm_id || $p->party_id != $bill->party_id) return false;
                        $pBills = array_map('trim', explode(',', $p->bill_no));
                        return count(array_intersect($pBills, $billChNos)) > 0;
                    });
                }
            }
            $bill->linked_payment = $payment;
        }

        return view('generate-bills-index', compact('parties', 'generateBills', 'firms'));
    }

    public function create()
    {
        $firms = Firm::getPermitted();
        $parties = Party::getPermitted();
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

        $action = $request->input('action', 'generate');
        $isDraft = $action === 'draft';

        $maxNumeric = GenerateBill::where('firm_id', $request->firm_id)
            ->where('party_id', $request->party_id)
            ->get()
            ->map(function ($bill) {
                return (int) preg_replace('/[^0-9]/', '', $bill->bill_no);
            })
            ->max();
            
        $nextId = $maxNumeric ? $maxNumeric + 1 : 1;
        $billNo = $request->bill_no ?? $nextId;

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
            'tds_percent' => $request->tds_percent ?? 1.00,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
            'is_draft' => $isDraft,
        ]);

        foreach ($request->items as $item) {
            if (!empty($item['ch_no']) || !empty($item['pcs'])) {
                $generateBill->items()->create([
                    'sr_no' => $item['sr_no'] ?? null,
                    'ch_no' => $item['ch_no'] ?? null,
                    'details' => $item['details'] ?? [],
                    'pcs' => $item['pcs'] ?? 0,
                    'rate' => $item['rate'] ?? 0,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }

        if ($isDraft) {
            return redirect()->route('generate-bills.index')->with('success', 'Draft saved successfully!');
        }

        return redirect()->route('generate-bills.index')->with('success', 'Generate Bill saved successfully!');
    }

    public function edit(GenerateBill $generateBill)
    {
        $firms = Firm::getPermitted();
        $parties = Party::getPermitted();
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

        $action = $request->input('action', 'generate');
        $isDraft = $action === 'draft';

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
            'tds_percent' => $request->tds_percent ?? $generateBill->tds_percent,
            'payment_date' => $request->payment_date,
            'payment_detail' => $request->payment_detail,
            'is_draft' => $isDraft,
        ]);

        $generateBill->items()->delete();

        foreach ($request->items as $item) {
            if (!empty($item['ch_no']) || !empty($item['pcs'])) {
                $generateBill->items()->create([
                    'sr_no' => $item['sr_no'] ?? null,
                    'ch_no' => $item['ch_no'] ?? null,
                    'details' => $item['details'] ?? [],
                    'pcs' => $item['pcs'] ?? 0,
                    'rate' => $item['rate'] ?? 0,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }

        if ($isDraft) {
            return redirect()->route('generate-bills.index')->with('success', 'Draft updated successfully!');
        }

        return redirect()->route('generate-bills.index')->with('success', 'Generate Bill updated successfully!');
    }

    public function destroy(GenerateBill $generateBill)
    {
        $generateBill->delete();
        return redirect()->route('generate-bills.index')->with('success', 'Generate Bill deleted successfully!');
    }

    public function print(Request $request, GenerateBill $generateBill)
    {
        $generateBill->load(['items', 'firm', 'party']);
        $isPreview = $request->has('preview');
        return view('generate-bill-print', compact('generateBill', 'isPreview'));
    }

    public function preview(Request $request)
    {
        try {
            $firm = Firm::find($request->firm_id);
            $party = Party::find($request->party_id);
            
            $generateBill = new GenerateBill([
                'firm_id' => $request->firm_id,
                'party_id' => $request->party_id,
                'bill_no' => $request->bill_no ?? 'AUTO',
                'date' => $request->date,
                'name' => $request->name,
                'add' => $request->add,
                'gst' => $request->gst,
                'vatav_percent' => $request->vatav_percent ?? 5.00,
                'sgst_percent' => $request->sgst_percent ?? 2.50,
                'cgst_percent' => $request->cgst_percent ?? 2.50,
                'tds_percent' => $request->tds_percent ?? 1.00,
            ]);

            $generateBill->setRelation('firm', $firm);
            $generateBill->setRelation('party', $party);

            $items = collect();
            if ($request->items) {
                foreach ($request->items as $item) {
                    if (!empty($item['ch_no']) || !empty($item['pcs'])) {
                        $items->push(new \App\Models\GenerateBillItem([
                            'sr_no' => $item['sr_no'] ?? null,
                            'ch_no' => $item['ch_no'] ?? null,
                            'details' => $item['details'] ?? [],
                            'pcs' => $item['pcs'] ?? 0,
                            'rate' => $item['rate'] ?? 0,
                            'amount' => $item['amount'] ?? 0,
                        ]));
                    }
                }
            }
            $generateBill->setRelation('items', $items);
            $isPreview = true;

            return view('generate-bill-print', compact('generateBill', 'isPreview'));
        } catch (\Exception $e) {
            return response($e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile(), 500);
        }
    }
}
