<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RcvdPaymentController extends Controller
{
    public function index()
    {
        $payments = \App\Models\RcvdPayment::with(['party', 'firm'])->orderBy('date', 'desc')->get();
        return view('rcvd-payment.index', compact('payments'));
    }

    public function create()
    {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('rcvd-payment.form', compact('firms', 'parties'));
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

        $data = $request->except('cheque_photo');

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
        $parties = \App\Models\Party::orderBy('name')->get();
        
        return view('rcvd-payment.form', [
            'firms' => $firms,
            'parties' => $parties,
            'editPayment' => $rcvdPayment
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

        $data = $request->except('cheque_photo');

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
