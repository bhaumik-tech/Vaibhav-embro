<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firm;
use App\Models\Party;
use App\Models\BankBook;

class BankBookController extends Controller
{
    public function index(Request $request)
    {
        $firms = Firm::getPermitted();
        $parties = Party::orderBy('name')->get();

        $selectedFirm = $request->firm_id;
        $selectedParty = $request->party_id;
        $selectedDate = $request->date;
        $selectedMonth = $request->month;
        $selectedYear = $request->year;

        $query = BankBook::with(['firm', 'party']);
        
        if ($selectedFirm) {
            $query->where('firm_id', $selectedFirm);
        }
        if ($selectedParty) {
            $query->where('party_id', $selectedParty);
        }
        if ($selectedDate) {
            $query->whereDate('date', $selectedDate);
        }
        if ($selectedMonth) {
            $query->whereMonth('date', $selectedMonth);
        }
        if ($selectedYear) {
            $query->whereYear('date', $selectedYear);
        }

        $transactions = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

        return view('bank-book.index', compact('firms', 'parties', 'selectedFirm', 'selectedParty', 'selectedDate', 'selectedMonth', 'selectedYear', 'transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'type' => 'required|in:received,pay',
            'amount' => 'required|numeric|min:0.01',
        ]);

        BankBook::create($request->all());

        return back()->with('success', 'Entry added successfully.');
    }

    public function destroy(BankBook $bankBook)
    {
        $bankBook->delete();
        return back()->with('success', 'Entry deleted successfully.');
    }
}
