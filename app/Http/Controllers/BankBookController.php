<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Firm;
use App\Models\Party;
use App\Models\BankBook;

class BankBookController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:bank_book,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:bank_book,remove', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $firms = Firm::getPermitted();
        $parties = Party::orderBy('name')->get();

        $selectedFirm = $request->firm_id;
        $selectedParty = $request->party_id;
        
        $selectedYear = $request->year ?: date('Y');
        $selectedMonth = $request->month;
        $selectedDate = $request->date;
        
        $showDetails = $request->has('month') || $request->has('date') || $request->has('view_all');

        if (!$showDetails) {
            // MONTHLY OVERVIEW
            $openingDateQuery = $selectedYear . '-01-01';
            
            $bbReceivedOB = BankBook::where('type', 'received')->whereDate('date', '<', $openingDateQuery);
            $bbPaidOB = BankBook::where('type', 'pay')->whereDate('date', '<', $openingDateQuery);
            $rcvdQueryOB = \App\Models\RcvdPayment::whereDate('date', '<', $openingDateQuery);

            if ($selectedFirm) {
                $bbReceivedOB->where('firm_id', $selectedFirm);
                $bbPaidOB->where('firm_id', $selectedFirm);
                $rcvdQueryOB->where('firm_id', $selectedFirm);
            }
            if ($selectedParty) {
                $bbReceivedOB->where('party_id', $selectedParty);
                $bbPaidOB->where('party_id', $selectedParty);
                $rcvdQueryOB->where('party_id', $selectedParty);
            }

            $yearOpeningBalance = ($bbReceivedOB->sum('amount') + $rcvdQueryOB->sum('amount')) - $bbPaidOB->sum('amount');

            // Fetch transactions for the year
            $bbYearQuery = BankBook::whereYear('date', $selectedYear);
            $rcvdYearQuery = \App\Models\RcvdPayment::whereYear('date', $selectedYear);
            
            if ($selectedFirm) {
                $bbYearQuery->where('firm_id', $selectedFirm);
                $rcvdYearQuery->where('firm_id', $selectedFirm);
            }
            if ($selectedParty) {
                $bbYearQuery->where('party_id', $selectedParty);
                $rcvdYearQuery->where('party_id', $selectedParty);
            }
            
            $bbYearTxs = $bbYearQuery->get(['date', 'amount', 'type']);
            $rcvdYearTxs = $rcvdYearQuery->get(['date', 'amount']);
            
            $monthlyData = [];
            $runningBalance = $yearOpeningBalance;
            
            for ($m = 1; $m <= 12; $m++) {
                $monthReceived = 0;
                $monthPaid = 0;
                
                foreach ($bbYearTxs as $tx) {
                    if ((int)date('m', strtotime($tx->date)) === $m) {
                        if ($tx->type === 'received') $monthReceived += $tx->amount;
                        if ($tx->type === 'pay') $monthPaid += $tx->amount;
                    }
                }
                foreach ($rcvdYearTxs as $tx) {
                    if ((int)date('m', strtotime($tx->date)) === $m) {
                        $monthReceived += $tx->amount;
                    }
                }
                
                $monthOpening = $runningBalance;
                $runningBalance = $runningBalance + $monthReceived - $monthPaid;
                $monthClosing = $runningBalance;
                
                $monthlyData[$m] = [
                    'month' => str_pad($m, 2, '0', STR_PAD_LEFT),
                    'month_name' => date('F', mktime(0, 0, 0, $m, 10)),
                    'opening' => $monthOpening,
                    'received' => $monthReceived,
                    'paid' => $monthPaid,
                    'closing' => $monthClosing,
                ];
            }
            
            return view('bank-book.months', compact('firms', 'parties', 'selectedFirm', 'selectedParty', 'selectedYear', 'monthlyData', 'yearOpeningBalance'));
        }

        // DETAILED TRANSACTIONS (Specific month or date)
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

        $bankBookTransactions = $query->get();

        $rcvdQuery = \App\Models\RcvdPayment::with(['firm', 'party']);
        
        if ($selectedFirm) {
            $rcvdQuery->where('firm_id', $selectedFirm);
        }
        if ($selectedParty) {
            $rcvdQuery->where('party_id', $selectedParty);
        }
        if ($selectedDate) {
            $rcvdQuery->whereDate('date', $selectedDate);
        }
        if ($selectedMonth) {
            $rcvdQuery->whereMonth('date', $selectedMonth);
        }
        if ($selectedYear) {
            $rcvdQuery->whereYear('date', $selectedYear);
        }

        $rcvdPayments = $rcvdQuery->get();
        $rcvdPayments->transform(function ($payment) {
            $payment->type = 'received';
            
            $ref = $payment->payment_type === 'Cheque' ? $payment->cheque_no : $payment->payment_type;
            if ($payment->bill_no) {
                $ref .= ' (Bill: ' . $payment->bill_no . ')';
            } elseif ($payment->bill_month) {
                $ref .= ' (Month: ' . $payment->bill_month . ')';
            }
            
            $payment->ref_no = $ref;
            $payment->is_rcvd_payment = true;
            return $payment;
        });

        $transactions = $bankBookTransactions->concat($rcvdPayments)
            ->sortBy(function($t) {
                return $t->date . '-' . $t->id;
            })->values();

        // Calculate opening balance
        $openingBalance = 0;
        $openingDateQuery = null;
        if ($selectedDate) {
            $openingDateQuery = $selectedDate;
        } elseif ($selectedMonth && $selectedYear) {
            $openingDateQuery = $selectedYear . '-' . $selectedMonth . '-01';
        } elseif ($selectedYear) {
            $openingDateQuery = $selectedYear . '-01-01';
        }

        if ($openingDateQuery) {
            $bbReceived = BankBook::where('type', 'received')->whereDate('date', '<', $openingDateQuery);
            $bbPaid = BankBook::where('type', 'pay')->whereDate('date', '<', $openingDateQuery);
            $rcvdQueryOB = \App\Models\RcvdPayment::whereDate('date', '<', $openingDateQuery);

            if ($selectedFirm) {
                $bbReceived->where('firm_id', $selectedFirm);
                $bbPaid->where('firm_id', $selectedFirm);
                $rcvdQueryOB->where('firm_id', $selectedFirm);
            }
            if ($selectedParty) {
                $bbReceived->where('party_id', $selectedParty);
                $bbPaid->where('party_id', $selectedParty);
                $rcvdQueryOB->where('party_id', $selectedParty);
            }

            $openingReceived = $bbReceived->sum('amount') + $rcvdQueryOB->sum('amount');
            $openingPaid = $bbPaid->sum('amount');
            
            $openingBalance = $openingReceived - $openingPaid;
        }

        return view('bank-book.index', compact('firms', 'parties', 'selectedFirm', 'selectedParty', 'selectedDate', 'selectedMonth', 'selectedYear', 'transactions', 'openingBalance', 'openingDateQuery'));
    }

    public function create()
    {
        $firms = Firm::getPermitted();
        $parties = Party::orderBy('name')->get();
        return view('bank-book.form', compact('firms', 'parties'));
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

        return redirect()->route('bank-book.index')->with('success', 'Entry added successfully.');
    }

    public function edit(BankBook $bankBook)
    {
        $firms = Firm::getPermitted();
        $parties = Party::orderBy('name')->get();
        $editEntry = $bankBook;
        return view('bank-book.form', compact('editEntry', 'firms', 'parties'));
    }

    public function update(Request $request, BankBook $bankBook)
    {
        $request->validate([
            'date' => 'required|date',
            'firm_id' => 'required|exists:firms,id',
            'party_id' => 'required|exists:parties,id',
            'type' => 'required|in:received,pay',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $bankBook->update($request->all());

        return redirect()->route('bank-book.index')->with('success', 'Entry updated successfully.');
    }

    public function destroy(BankBook $bankBook)
    {
        $bankBook->delete();
        return back()->with('success', 'Entry deleted successfully.');
    }
}
