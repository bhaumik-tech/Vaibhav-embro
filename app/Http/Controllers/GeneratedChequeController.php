<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneratedChequeController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\GeneratedCheque::with('firm')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('payee_name', 'like', '%' . $request->search . '%')
                  ->orWhere('bill_no', 'like', '%' . $request->search . '%')
                  ->orWhere('amount', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('filter_firm_id')) {
            $query->where('firm_id', $request->filter_firm_id);
        }

        if ($request->filled('filter_date_from')) {
            $query->whereDate('date', '>=', $request->filter_date_from);
        }

        if ($request->filled('filter_date_to')) {
            $query->whereDate('date', '<=', $request->filter_date_to);
        }

        $cheques = $query->paginate(25)->withQueryString();
        $firms = \App\Models\Firm::getPermitted();

        return view('generate-cheques-index', compact('cheques', 'firms'));
    }

    public function create()
    {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        $threadCompanies = \DB::table('thread_box_setups')->select('company_name')->distinct()->pluck('company_name')->filter();
        return view('generate-cheque', compact('firms', 'parties', 'threadCompanies'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'is_ac_payee' => 'boolean',
            'date' => 'required|date',
            'payee_name' => 'nullable|string',
            'firm_id' => 'nullable|exists:firms,id',
            'remark' => 'nullable|string',
            'bill_no' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $validated['is_ac_payee'] = $request->has('is_ac_payee');

        $cheque = \App\Models\GeneratedCheque::create($validated);

        return redirect()->route('generate-cheque.print', $cheque->id)->with('success', 'Cheque details saved successfully.');
    }

    public function edit(\App\Models\GeneratedCheque $cheque)
    {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        $threadCompanies = \DB::table('thread_box_setups')->select('company_name')->distinct()->pluck('company_name')->filter();
        return view('generate-cheque-edit', compact('cheque', 'firms', 'parties', 'threadCompanies'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\GeneratedCheque $cheque)
    {
        $validated = $request->validate([
            'is_ac_payee' => 'boolean',
            'date' => 'required|date',
            'payee_name' => 'nullable|string',
            'firm_id' => 'nullable|exists:firms,id',
            'remark' => 'nullable|string',
            'bill_no' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $validated['is_ac_payee'] = $request->has('is_ac_payee');

        $cheque->update($validated);

        return redirect()->route('generate-cheques.index')->with('success', 'Cheque details updated successfully.');
    }

    public function show(\App\Models\GeneratedCheque $cheque)
    {
        return view('generate-cheque-show', compact('cheque'));
    }

    public function print(\App\Models\GeneratedCheque $cheque)
    {
        return view('generate-cheque-print', compact('cheque'));
    }

    public function destroy(\App\Models\GeneratedCheque $cheque)
    {
        $cheque->delete();
        return back()->with('success', 'Cheque record deleted successfully.');
    }
}
