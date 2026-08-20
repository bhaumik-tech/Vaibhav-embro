<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RcvdPaymentController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $parties = \App\Models\Party::getPermitted();
        
        if (!$request->filled('party_id') && $parties->isNotEmpty()) {
            return redirect()->route('rcvd-payment.index', array_merge($request->query(), ['party_id' => $parties->first()->id]));
        }

        $query = \App\Models\RcvdPayment::with(['party', 'firm']);
        
        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }
        
        $permittedFirmIds = \App\Models\Firm::getPermitted()->pluck('id')->toArray();
        if (!auth()->user()->isAdmin()) {
            $query->whereIn('firm_id', $permittedFirmIds);
        }
        
        $payments = $query->orderBy('date', 'desc')->get();
        return view('rcvd-payment.index', compact('payments', 'parties'));
    }

    public function show(\App\Models\RcvdPayment $rcvdPayment)
    {
        return view('rcvd-payment.show', compact('rcvdPayment'));
    }

    public function create()
    {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::getPermitted();
        
        $paidBillNos = \App\Models\RcvdPayment::whereNotNull('bill_no')->pluck('bill_no')
            ->flatMap(function($item) {
                return array_map('trim', explode(',', $item));
            })->toArray();
        $unpaidBills = \App\Models\GenerateBill::with('items')
                            ->whereNotIn('bill_no', $paidBillNos)
                            ->where('is_draft', false)
                            ->get()
                            ->each(function($bill) {
                                $bill->append('net_amount');
                            })
                            ->sortBy(function($item) {
                                return (int) preg_replace('/[^0-9]/', '', $item->bill_no);
                            })
                            ->values();

        return view('rcvd-payment.form', compact('firms', 'parties', 'unpaidBills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'party_id' => 'required|exists:parties,id',
            'firm_id' => 'required|exists:firms,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:RTGS,Cheque',
            'cheque_photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->except(['cheque_photo', 'payment_against']);

        // Handle disabled fields not being sent
        if ($request->payment_against === 'monthly') {
            $data['bill_month'] = $request->bill_month;
            $data['bill_no'] = null;
        } elseif ($request->payment_against === 'advanced') {
            $data['bill_month'] = null;
            $data['bill_no'] = null;
        } else {
            $data['bill_no'] = is_array($request->bill_no) ? implode(', ', $request->bill_no) : $request->bill_no;
            $data['bill_month'] = null;
        }

        if ($request->hasFile('cheque_photo')) {
            $path = $request->file('cheque_photo')->store('cheque_photos', 'public');
            $data['cheque_photo'] = $path;
        }

        \App\Models\RcvdPayment::create($data);

        return redirect()->route('rcvd-payment.index')->with('success', 'Received Payment Entry stored successfully.');
    }

    public function edit(\App\Models\RcvdPayment $rcvdPayment)
    {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::getPermitted();
        
        $paidBillNos = \App\Models\RcvdPayment::whereNotNull('bill_no')
                        ->where('id', '!=', $rcvdPayment->id)
                        ->pluck('bill_no')
                        ->flatMap(function($item) {
                            return array_map('trim', explode(',', $item));
                        })->toArray();
                        
        $unpaidBills = \App\Models\GenerateBill::with('items')
                            ->whereNotIn('bill_no', $paidBillNos)
                            ->where('is_draft', false)
                            ->get()
                            ->each(function($bill) {
                                $bill->append('net_amount');
                            })
                            ->sortBy(function($item) {
                                return (int) preg_replace('/[^0-9]/', '', $item->bill_no);
                            })
                            ->values();
        
        return view('rcvd-payment.form', [
            'firms' => $firms,
            'parties' => $parties,
            'editPayment' => $rcvdPayment,
            'unpaidBills' => $unpaidBills
        ]);
    }

    public function update(Request $request, \App\Models\RcvdPayment $rcvdPayment)
    {
        $request->validate([
            'date' => 'required|date',
            'party_id' => 'required|exists:parties,id',
            'firm_id' => 'required|exists:firms,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:RTGS,Cheque',
            'cheque_photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->except(['cheque_photo', 'payment_against']);

        // Handle disabled fields not being sent
        if ($request->payment_against === 'monthly') {
            $data['bill_month'] = $request->bill_month;
            $data['bill_no'] = null;
        } elseif ($request->payment_against === 'advanced') {
            $data['bill_month'] = null;
            $data['bill_no'] = null;
        } else {
            $data['bill_no'] = is_array($request->bill_no) ? implode(', ', $request->bill_no) : $request->bill_no;
            $data['bill_month'] = null;
        }

        if ($request->hasFile('cheque_photo')) {
            $path = $request->file('cheque_photo')->store('cheque_photos', 'public');
            $data['cheque_photo'] = $path;
        }

        $rcvdPayment->update($data);

        return redirect()->route('rcvd-payment.index')->with('success', 'Received Payment Entry updated successfully.');
    }

    public function destroy(\App\Models\RcvdPayment $rcvdPayment)
    {
        $rcvdPayment->delete();
        return redirect()->route('rcvd-payment.index')->with('success', 'Entry deleted successfully.');
    }
}
