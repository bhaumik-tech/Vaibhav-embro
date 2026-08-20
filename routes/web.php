<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\RcvdPaymentController;

use App\Http\Controllers\InputChalanController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/run-migrations', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return "Migrations executed successfully! Output: " . \Illuminate\Support\Facades\Artisan::output();
});
// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $firms = \App\Models\Firm::getPermitted()->load('machines');
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('dashboard', compact('firms', 'parties'));
    })->middleware('page.permission:dashboard,view');

    Route::get('/make-program', function () {
        $firms = \App\Models\Firm::getPermitted()->load('machines');
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('make-program', compact('firms', 'parties'));
    })->name('make-program')->middleware('page.permission:make_program,view');

    Route::get('/make-program/party/{party}', function (\App\Models\Party $party) {
        $programs = \App\Models\Program::with(['firm', 'machine', 'party'])
            ->where('party_id', $party->id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();
        return view('make-program-party', compact('party', 'programs'));
    })->name('make-program.party.show')->middleware('page.permission:make_program,view');

    Route::get('/make-program/{firm}/machines', function (\App\Models\Firm $firm) {
        $firm->load('machines');
        return view('make-program-machines', compact('firm'));
    })->name('make-program.machines')->middleware('page.permission:make_program,view');

    Route::get('/make-program/{firm}/machines/{machine}', function (\App\Models\Firm $firm, \App\Models\Machine $machine) {
        $programs = \App\Models\Program::with('party')
            ->where('firm_id', $firm->id)
            ->where('machine_id', $machine->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $dropdownOptions = \App\Models\DropdownOption::with('children')->whereNull('parent_id')->get()->groupBy('column_name');
        return view('make-program-machine-blank', compact('firm', 'machine', 'programs', 'dropdownOptions'));
    })->name('make-program.machine.show')->middleware('page.permission:make_program,view');

    Route::get('/make-program/{firm}/machines/{machine}/programs/create', function (\App\Models\Firm $firm, \App\Models\Machine $machine) {
        $parties = \App\Models\Party::orderBy('name')->get();
        $dropdownOptions = \App\Models\DropdownOption::with('children')->whereNull('parent_id')->get()->groupBy('column_name');
        return view('make-program-create', compact('firm', 'machine', 'parties', 'dropdownOptions'));
    })->name('make-program.program.create')->middleware('page.permission:make_program,edit');

    Route::post('/make-program/{firm}/machines/{machine}/programs', function (\Illuminate\Http\Request $request, \App\Models\Firm $firm, \App\Models\Machine $machine) {
        $data = $request->validate([
            'party_id' => 'nullable|exists:parties,id',
            'ch_no' => 'nullable|string',
            'chart' => 'nullable|string',
            'mtr' => 'nullable|numeric',
            'pcs' => 'nullable|integer',
            'rs' => 'nullable|numeric',
            'process' => 'nullable|string',
            'work_percent' => 'nullable|numeric|min:0|max:100',
            'time' => 'nullable',
            'date' => 'nullable|date',
            'detail' => 'nullable|string',
            'design_code' => 'nullable|string',
            'note' => 'nullable|string',
            'is_live' => 'boolean',
        ]);

        $data['firm_id'] = $firm->id;
        $data['machine_id'] = $machine->id;

        \App\Models\Program::create($data);

        return redirect()->route('make-program.machine.show', ['firm' => $firm->id, 'machine' => $machine->id]);
    })->name('make-program.program.store')->middleware('page.permission:make_program,edit');


    Route::get('/make-program/{firm}/machines/{machine}/programs/{program}/edit', function (\App\Models\Firm $firm, \App\Models\Machine $machine, \App\Models\Program $program) {
        $parties = \App\Models\Party::orderBy('name')->get();
        $dropdownOptions = \App\Models\DropdownOption::with('children')->whereNull('parent_id')->get()->groupBy('column_name');
        return view('make-program-edit', compact('firm', 'machine', 'program', 'parties', 'dropdownOptions'));
    })->name('make-program.program.edit')->middleware('page.permission:make_program,edit');

    Route::put('/make-program/{firm}/machines/{machine}/programs/{program}', function (\Illuminate\Http\Request $request, \App\Models\Firm $firm, \App\Models\Machine $machine, \App\Models\Program $program) {
        $data = $request->validate([
            'party_id' => 'nullable|exists:parties,id',
            'ch_no' => 'nullable|string',
            'chart' => 'nullable|string',
            'mtr' => 'nullable|numeric',
            'pcs' => 'nullable|integer',
            'rs' => 'nullable|numeric',
            'process' => 'nullable|string',
            'work_percent' => 'nullable|numeric|min:0|max:100',
            'time' => 'nullable',
            'date' => 'nullable|date',
            'detail' => 'nullable|string',
            'design_code' => 'nullable|string',
            'note' => 'nullable|string',
            'is_live' => 'boolean',
        ]);

        $program->update($data);

        if ($data['process'] === 'R.D') {
            return redirect()->route('ready-to-delivery')->with('success', 'Program marked as Ready to Delivery');
        }

        return redirect()->route('make-program.machine.show', ['firm' => $firm->id, 'machine' => $machine->id])->with('success', 'Program updated successfully');
    })->name('make-program.program.update')->middleware('page.permission:make_program,edit');

    Route::patch('/make-program/{firm}/machines/{machine}/programs/{program}/process', function (\Illuminate\Http\Request $request, \App\Models\Firm $firm, \App\Models\Machine $machine, \App\Models\Program $program) {
        $request->validate(['process' => 'required|string']);
        $program->update(['process' => $request->process]);
        
        if ($request->process === 'R.D') {
            return response()->json(['redirect' => route('ready-to-delivery')]);
        }
        
        return response()->json(['success' => true]);
    })->name('make-program.program.update-process')->middleware('page.permission:make_program,edit');

    Route::patch('/make-program/{firm}/machines/{machine}/programs/{program}/work-percent', function (\Illuminate\Http\Request $request, \App\Models\Firm $firm, \App\Models\Machine $machine, \App\Models\Program $program) {
        $request->validate(['work_percent' => 'nullable|numeric|min:0|max:100']);
        $program->update(['work_percent' => $request->work_percent]);
        
        return response()->json(['success' => true]);
    })->name('make-program.program.update-work-percent')->middleware('page.permission:make_program,edit');

    Route::patch('/make-program/{firm}/machines/{machine}/programs/{program}/live', function (\Illuminate\Http\Request $request, \App\Models\Firm $firm, \App\Models\Machine $machine, \App\Models\Program $program) {
        $request->validate(['is_live' => 'required|boolean']);
        $program->update(['is_live' => $request->is_live]);
        
        return response()->json(['success' => true]);
    })->name('make-program.program.update-live')->middleware('page.permission:make_program,edit');

    Route::get('/ready-to-delivery', function () {
        $programs = \App\Models\Program::with(['firm', 'machine', 'party'])
            ->where('process', 'R.D')
            ->orderBy('date', 'desc')
            ->get();
        return view('ready-to-delivery', compact('programs'));
    })->name('ready-to-delivery')->middleware('page.permission:ready_to_delivery,view');

    Route::patch('/programs/{program}/deliver', function (\App\Models\Program $program) {
        $program->update(['process' => 'DELIVERED']);
        return response()->json(['success' => true]);
    })->name('program.deliver')->middleware('page.permission:ready_to_delivery,edit');

    Route::get('/todays-delivery', function () {
        $programs = \App\Models\Program::with(['firm', 'machine', 'party'])
            ->where('process', 'DELIVERED')
            ->whereDate('updated_at', \Carbon\Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('todays-delivery', compact('programs'));
    })->name('todays-delivery')->middleware('page.permission:todays_delivery,view');

    Route::get('/check-status', function () {
        $firms = \App\Models\Firm::getPermitted()->load('machines');
        return view('check-status-firms', compact('firms'));
    })->name('check-status')->middleware('page.permission:check_status,view');

    Route::get('/check-status/{firm}', function (\App\Models\Firm $firm) {
        $programs = \App\Models\Program::with(['firm', 'machine', 'party'])
            ->where('firm_id', $firm->id)
            ->where('process', '!=', 'R.D')
            ->where('is_live', 1)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();
        return view('check-status', compact('firm', 'programs'));
    })->name('check-status.firm')->middleware('page.permission:check_status,view');

    Route::delete('/make-program/{firm}/machines/{machine}/programs/{program}', function (\App\Models\Firm $firm, \App\Models\Machine $machine, \App\Models\Program $program) {
        $program->delete();
        return back()->with('success', 'Program removed successfully');
    })->name('make-program.program.destroy')->middleware('page.permission:make_program,remove');

    Route::get('/firm/{firm}/machines', function (\App\Models\Firm $firm) {
        $firm->load('machines');
        return view('firm-machines', compact('firm'));
    })->name('firm.machines')->middleware('page.permission:dashboard,view');

    Route::get('/register', function () {
        $parties = \App\Models\Party::orderBy('name')->get();
        
        if (!request('party_id') && $parties->isNotEmpty()) {
            return redirect()->route('register.index', array_merge(request()->query(), ['party_id' => $parties->first()->id]));
        }

        $query = \App\Models\InputChalan::with(['items', 'firm']);
        $outQuery = \App\Models\OutputChalan::with(['firm']);
        if (request('party_id')) {
            $query->where('party_id', request('party_id'));
            $outQuery->where('party_id', request('party_id'));
        }

        if (request('filter_firm_id')) {
            $query->where('firm_id', request('filter_firm_id'));
            $outQuery->where('firm_id', request('filter_firm_id'));
        }

        if (request('filter_date_from') && request('filter_date_to')) {
            $query->whereBetween('date', [request('filter_date_from'), request('filter_date_to')]);
            $outQuery->whereBetween('date', [request('filter_date_from'), request('filter_date_to')]);
        } elseif (request('filter_date_from')) {
            $query->whereDate('date', '>=', request('filter_date_from'));
            $outQuery->whereDate('date', '>=', request('filter_date_from'));
        } elseif (request('filter_date_to')) {
            $query->whereDate('date', '<=', request('filter_date_to'));
            $outQuery->whereDate('date', '<=', request('filter_date_to'));
        }

        $status = request('status', 'pending');
        if ($status === 'done') {
            $outQuery->where('is_done', 1);
        } else {
            $outQuery->where('is_done', 0);
        }

        $timeframe = request('timeframe');
        if ($timeframe === 'current_month') {
            $start = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $end = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            $query->whereBetween('date', [$start, $end]);
            $outQuery->whereBetween('date', [$start, $end]);
        } elseif ($timeframe === 'last_month') {
            $start = \Carbon\Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $end = \Carbon\Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $query->whereBetween('date', [$start, $end]);
            $outQuery->whereBetween('date', [$start, $end]);
        }

        $outputChalans = clone $outQuery;
        $outputChalansList = $outputChalans->latest('date')->get()->map(function ($ch) {
            $ch->source_type = 'output';
            return $ch;
        });
        $outputChalans = $outputChalansList;

        $genQuery = \App\Models\GenerateChalan::with(['firm', 'items']);
        if (request('party_id')) {
            $genQuery->where('party_id', request('party_id'));
        }
        if (request('filter_firm_id')) {
            $genQuery->where('firm_id', request('filter_firm_id'));
        }
        if (request('filter_date_from') && request('filter_date_to')) {
            $genQuery->whereBetween('date', [request('filter_date_from'), request('filter_date_to')]);
        } elseif (request('filter_date_from')) {
            $genQuery->whereDate('date', '>=', request('filter_date_from'));
        } elseif (request('filter_date_to')) {
            $genQuery->whereDate('date', '<=', request('filter_date_to'));
        }

        if ($status === 'done') {
            $genQuery->where('is_done', 1);
        } else {
            $genQuery->where('is_done', 0);
        }
        if ($timeframe === 'current_month' || $timeframe === 'last_month') {
            $genQuery->whereBetween('date', [$start, $end]);
        }
        $genChalans = $genQuery->latest('date')->get()->map(function ($ch) {
            $ch->source_type = 'generate';
            $ch->total_pcs = $ch->items->sum('pcs');
            $ch->total_amount = $ch->items->sum('amount');
            $ch->party_chalan_no = $ch->party_ch;
            return $ch;
        });

        $genChalansList = $genChalans->sortBy(function($item) {
            return (int) preg_replace('/[^0-9]/', '', $item->chalan_no);
        })->values();

        $inputChalansList = $query->get()->filter(function($chalan) use ($status) {
            $tPcs = $chalan->items->sum('pcs');
            $chNoInt = (int) $chalan->chalan_no;
            
            $relatedChalans = \App\Models\GenerateChalan::where('party_id', $chalan->party_id)
                ->whereHas('items', function($q) use ($chNoInt) {
                    $q->whereRaw('CAST(ch_no AS UNSIGNED) = ?', [$chNoInt]);
                })->with('items')->get();

            $outPcs = 0;
            foreach ($relatedChalans as $gc) {
                $explicitPcs = 0;
                $blankPcs = 0;
                $otherChNosExist = false;
                
                foreach ($gc->items as $gItem) {
                    $itemChNoInt = (int)$gItem->ch_no;
                    if ($itemChNoInt === $chNoInt) {
                        $explicitPcs += $gItem->pcs;
                    } elseif (empty($gItem->ch_no) || trim($gItem->ch_no) === '-' || $itemChNoInt === 0) {
                        $blankPcs += $gItem->pcs;
                    } else {
                        $otherChNosExist = true;
                    }
                }
                
                $outPcs += $explicitPcs;
                if (!$otherChNosExist) {
                    $outPcs += $blankPcs;
                }
            }
            
            $isMatching = ($tPcs > 0 && $tPcs <= $outPcs);
            $isRowDone = $chalan->is_done || $isMatching;
            
            return $status === 'done' ? $isRowDone : !$isRowDone;
        })->sortBy(function($item) {
            return (int) preg_replace('/[^0-9]/', '', $item->chalan_no);
        })->values();

        // Fetch associated bills for generate chalans
        $billItemsQuery = \App\Models\GenerateBillItem::with('generateBill.firm')
            ->whereHas('generateBill', function ($query) {
                if (request('party_id')) {
                    $query->where('party_id', request('party_id'));
                }
                $query->where('is_draft', false);
            });
        
        $billItems = $billItemsQuery->get();
        $chalanBillMap = [];
        
        foreach ($billItems as $bItem) {
            if ($bItem->generateBill) {
                $srNos = array_map('trim', explode(',', $bItem->sr_no));
                foreach ($srNos as $srNo) {
                    if (!empty($srNo)) {
                        $key = $srNo . '_' . $bItem->generateBill->party_id;
                        $chalanBillMap[$key] = $bItem->generateBill;
                    }
                }
            }
        }
        
        foreach ($genChalansList as $ch) {
            $key = $ch->chalan_no . '_' . $ch->party_id;
            $ch->linked_bill = $chalanBillMap[$key] ?? null;
        }

        $genChalans = $genChalansList;

        $inputChalans = $inputChalansList;

        // Fetch bills for the Bills tab
        $billsQuery = \App\Models\GenerateBill::with(['firm', 'party']);
        if (request('party_id')) {
            $billsQuery->where('party_id', request('party_id'));
        }
        if (request('filter_firm_id')) {
            $billsQuery->where('firm_id', request('filter_firm_id'));
        }
        if (request('filter_date_from') && request('filter_date_to')) {
            $billsQuery->whereBetween('date', [request('filter_date_from'), request('filter_date_to')]);
        } elseif (request('filter_date_from')) {
            $billsQuery->whereDate('date', '>=', request('filter_date_from'));
        } elseif (request('filter_date_to')) {
            $billsQuery->whereDate('date', '<=', request('filter_date_to'));
        }
        if ($timeframe === 'current_month' || $timeframe === 'last_month') {
            $billsQuery->whereBetween('date', [$start, $end]);
        }
        $registerBillsList = $billsQuery->latest('date')->get();
        $registerBills = $registerBillsList;

        $firms = \App\Models\Firm::getPermitted();

        return view('register', compact('parties', 'inputChalans', 'outputChalans', 'genChalans', 'registerBills', 'firms'));
    })->name('register.index')->middleware('page.permission:registers,view');

    Route::get('/register/print', function () {
        $parties = \App\Models\Party::orderBy('name')->get();
        
        if (!request('party_id') && $parties->isNotEmpty()) {
            return redirect()->route('register.print', array_merge(request()->query(), ['party_id' => $parties->first()->id]));
        }

        $query = \App\Models\InputChalan::with(['items', 'firm']);
        $outQuery = \App\Models\OutputChalan::with(['firm']);
        if (request('party_id')) {
            $query->where('party_id', request('party_id'));
            $outQuery->where('party_id', request('party_id'));
        }

        if (request('filter_firm_id')) {
            $query->where('firm_id', request('filter_firm_id'));
            $outQuery->where('firm_id', request('filter_firm_id'));
        }

        if (request('filter_date')) {
            $query->whereDate('date', request('filter_date'));
            $outQuery->whereDate('date', request('filter_date'));
        }

        $status = request('status', 'pending');
        if ($status === 'done') {
            $outQuery->where('is_done', 1);
        } else {
            $outQuery->where('is_done', 0);
        }

        $timeframe = request('timeframe');
        if ($timeframe === 'current_month') {
            $start = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $end = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            $query->whereBetween('date', [$start, $end]);
            $outQuery->whereBetween('date', [$start, $end]);
        } elseif ($timeframe === 'last_month') {
            $start = \Carbon\Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $end = \Carbon\Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $query->whereBetween('date', [$start, $end]);
            $outQuery->whereBetween('date', [$start, $end]);
        }

        $outputChalans = clone $outQuery;
        $outputChalans = $outputChalans->oldest('date')->get()->map(function ($ch) {
            $ch->source_type = 'output';
            return $ch;
        });

        $genQuery = \App\Models\GenerateChalan::with(['firm', 'items']);
        if (request('party_id')) {
            $genQuery->where('party_id', request('party_id'));
        }
        if (request('filter_firm_id')) {
            $genQuery->where('firm_id', request('filter_firm_id'));
        }
        if (request('filter_date')) {
            $genQuery->whereDate('date', request('filter_date'));
        }

        if ($status === 'done') {
            $genQuery->where('is_done', 1);
        } else {
            $genQuery->where('is_done', 0);
        }
        if ($timeframe === 'current_month' || $timeframe === 'last_month') {
            $genQuery->whereBetween('date', [$start, $end]);
        }
        $genChalans = $genQuery->oldest('date')->get()->map(function ($ch) {
            $ch->source_type = 'generate';
            $ch->total_pcs = $ch->items->sum('pcs');
            $ch->total_amount = $ch->items->sum('amount');
            $ch->party_chalan_no = $ch->party_ch;
            return $ch;
        });

        $mergedOutputs = collect()->concat($outputChalans)->concat($genChalans)->sortBy(function($item) {
            return (int) preg_replace('/[^0-9]/', '', $item->chalan_no);
        })->values();

        $inputChalans = $query->get()->filter(function($chalan) use ($status) {
            $tPcs = $chalan->items->sum('pcs');
            $chNoInt = (int) $chalan->chalan_no;
            
            $relatedChalans = \App\Models\GenerateChalan::where('party_id', $chalan->party_id)
                ->whereHas('items', function($q) use ($chNoInt) {
                    $q->whereRaw('CAST(ch_no AS UNSIGNED) = ?', [$chNoInt]);
                })->with('items')->get();

            $outPcs = 0;
            foreach ($relatedChalans as $gc) {
                $explicitPcs = 0;
                $blankPcs = 0;
                $otherChNosExist = false;
                
                foreach ($gc->items as $gItem) {
                    $itemChNoInt = (int)$gItem->ch_no;
                    if ($itemChNoInt === $chNoInt) {
                        $explicitPcs += $gItem->pcs;
                    } elseif (empty($gItem->ch_no) || trim($gItem->ch_no) === '-' || $itemChNoInt === 0) {
                        $blankPcs += $gItem->pcs;
                    } else {
                        $otherChNosExist = true;
                    }
                }
                
                $outPcs += $explicitPcs;
                if (!$otherChNosExist) {
                    $outPcs += $blankPcs;
                }
            }
            
            $isMatching = ($tPcs > 0 && $tPcs <= $outPcs);
            $isRowDone = $chalan->is_done || $isMatching;
            
            return $status === 'done' ? $isRowDone : !$isRowDone;
        })->sortBy(function($item) {
            return (int) preg_replace('/[^0-9]/', '', $item->chalan_no);
        })->values();
        $outputChalans = $mergedOutputs;

        $party = request('party_id') ? \App\Models\Party::find(request('party_id')) : null;

        return view('register-print', compact('inputChalans', 'outputChalans', 'party'));
    })->name('register.print')->middleware('page.permission:registers,view');

    Route::get('/input-chalan', function () {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::with('firms')->orderBy('name')->get();
        $dropdownOptions = \App\Models\DropdownOption::with('children')->whereNull('parent_id')->get()->groupBy('column_name');
        return view('input-chalan', compact('firms', 'parties', 'dropdownOptions'));
    })->middleware('page.permission:input_chalan,edit');

    Route::post('/input-chalan', [InputChalanController::class, 'store'])->name('input-chalan.store');
    Route::post('/input-chalan/quick-store', [InputChalanController::class, 'quickStore'])->name('input-chalan.quick-store');
    Route::get('/input-chalan/{inputChalan}/edit', [InputChalanController::class, 'edit'])->name('input-chalan.edit');
    Route::put('/input-chalan/{inputChalan}', [InputChalanController::class, 'update'])->name('input-chalan.update');
    Route::delete('/input-chalan/{inputChalan}', [InputChalanController::class, 'destroy'])->name('input-chalan.destroy');
    Route::post('/input-chalans/{inputChalan}/toggle-done', function (\App\Models\InputChalan $inputChalan) {
        $inputChalan->is_done = !$inputChalan->is_done;
        $inputChalan->save();
        return back()->with('success', 'Chalan status updated!');
    })->name('input-chalans.toggle-done')->middleware('page.permission:input_chalan,edit');

    Route::get('/generate-chalans', [App\Http\Controllers\GenerateChalanController::class, 'index'])->name('generate-chalans.index')->middleware('page.permission:generate_chalan,view');
    Route::get('/generate-chalan', function () {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('generate-chalan', compact('firms', 'parties'));
    })->name('generate-chalan.create')->middleware('page.permission:generate_chalan,edit');
    Route::post('/generate-chalans/preview', [App\Http\Controllers\GenerateChalanController::class, 'preview'])->name('generate-chalans.preview');
    Route::post('/generate-chalans', [App\Http\Controllers\GenerateChalanController::class, 'store'])->name('generate-chalans.store');
    Route::get('/generate-chalans/{generateChalan}/edit', [App\Http\Controllers\GenerateChalanController::class, 'edit'])->name('generate-chalans.edit');
    Route::put('/generate-chalans/{generateChalan}', [App\Http\Controllers\GenerateChalanController::class, 'update'])->name('generate-chalans.update');
    Route::delete('/generate-chalans/{generateChalan}', [App\Http\Controllers\GenerateChalanController::class, 'destroy'])->name('generate-chalans.destroy');
    Route::post('/generate-chalans/{generateChalan}/toggle-done', function (\App\Models\GenerateChalan $generateChalan) {
        $generateChalan->is_done = !$generateChalan->is_done;
        $generateChalan->save();
        return back()->with('success', 'Generate Chalan status updated!');
    })->name('generate-chalans.toggle-done')->middleware('page.permission:generate_chalan,edit');
    Route::post('/generate-chalans/print-bulk', [App\Http\Controllers\GenerateChalanController::class, 'printBulk'])->name('generate-chalans.print-bulk')->middleware('page.permission:generate_chalan,view');
    Route::get('/generate-chalans/{generateChalan}/print', [App\Http\Controllers\GenerateChalanController::class, 'print'])->name('generate-chalans.print')->middleware('page.permission:generate_chalan,view');

    // Output Chalans (Register Side & Separate Page)
    Route::get('/output-chalans', [App\Http\Controllers\OutputChalanController::class, 'create'])->name('output-chalans.create')->middleware('page.permission:output_chalan,edit');
    Route::post('/output-chalans', [App\Http\Controllers\OutputChalanController::class, 'store'])->name('output-chalans.store');
    Route::post('/output-chalans/quick-store', [App\Http\Controllers\OutputChalanController::class, 'quickStore'])->name('output-chalans.quick-store');
    Route::get('/output-chalans/{outputChalan}/edit', [App\Http\Controllers\OutputChalanController::class, 'edit'])->name('output-chalans.edit');
    Route::put('/output-chalans/{outputChalan}', [App\Http\Controllers\OutputChalanController::class, 'update'])->name('output-chalans.update');
    Route::delete('/output-chalans/{outputChalan}', [App\Http\Controllers\OutputChalanController::class, 'destroy'])->name('output-chalans.destroy');
    Route::post('/output-chalans/{outputChalan}/toggle-done', function (\App\Models\OutputChalan $outputChalan) {
        $outputChalan->is_done = !$outputChalan->is_done;
        $outputChalan->save();
        return back()->with('success', 'Output Chalan status updated!');
    })->name('output-chalans.toggle-done')->middleware('page.permission:output_chalan,edit');

    Route::get('/api/input-chalans/by-no/{chalan_no}', function ($chalan_no) {
        $chalan = \App\Models\InputChalan::with(['items', 'party'])->where('chalan_no', $chalan_no)->first();
        if ($chalan) {
            return response()->json($chalan);
        }
        return response()->json(['error' => 'Not found'], 404);
    })->middleware('page.permission:output_chalan,view');

    Route::get('/api/generate-chalans/by-no/{chalan_no}', function ($chalan_no) {
        $chalan = \App\Models\GenerateChalan::with(['items', 'party'])->where('chalan_no', $chalan_no);
        if (request('party_id')) {
            $chalan->where('party_id', request('party_id'));
        }
        $chalan = $chalan->first();
        if ($chalan) {
            return response()->json($chalan);
        }
        return response()->json(['error' => 'Not found'], 404);
    })->middleware('page.permission:generate_bill,view');

    Route::get('/api/parties/{party_id}/chalans', function ($party_id) {
        $include_chalan = request('include_chalan');
        
        $chalans = \App\Models\InputChalan::with('items')
            ->where('party_id', $party_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $validChalans = [];

        foreach ($chalans as $chalan) {
            if ($include_chalan && $chalan->chalan_no == $include_chalan) {
                if (!in_array($chalan->chalan_no, $validChalans)) {
                    $validChalans[] = $chalan->chalan_no;
                }
                continue;
            }

            if ($chalan->is_done) {
                continue;
            }

            $tPcs = $chalan->items->sum('pcs');
            $chNoInt = (int) $chalan->chalan_no;
            
            $relatedChalans = \App\Models\GenerateChalan::where('party_id', $chalan->party_id)
                ->whereHas('items', function($q) use ($chNoInt) {
                    $q->whereRaw('CAST(ch_no AS UNSIGNED) = ?', [$chNoInt]);
                })->with('items')->get();

            $outPcs = 0;
            foreach ($relatedChalans as $gc) {
                $explicitPcs = 0;
                $blankPcs = 0;
                $otherChNosExist = false;
                
                foreach ($gc->items as $gItem) {
                    $itemChNoInt = (int)$gItem->ch_no;
                    if ($itemChNoInt === $chNoInt) {
                        $explicitPcs += $gItem->pcs;
                    } elseif (empty($gItem->ch_no) || trim($gItem->ch_no) === '-' || $itemChNoInt === 0) {
                        $blankPcs += $gItem->pcs;
                    } else {
                        $otherChNosExist = true;
                    }
                }
                
                $outPcs += $explicitPcs;
                if (!$otherChNosExist) {
                    $outPcs += $blankPcs;
                }
            }
            
            $isMatching = ($tPcs > 0 && $tPcs <= $outPcs);
            $isRowDone = $chalan->is_done || $isMatching;

            if (!$isRowDone) {
                if (!in_array($chalan->chalan_no, $validChalans)) {
                    $validChalans[] = $chalan->chalan_no;
                }
            }
        }

        return response()->json(array_values($validChalans));
    });

    Route::get('/api/parties/{party_id}/chalans/{chalan_no}', function ($party_id, $chalan_no) {
        $chalan = \App\Models\InputChalan::with(['items'])->where('party_id', $party_id)->where('chalan_no', $chalan_no)->first();
        if ($chalan) {
            return response()->json($chalan);
        }
        return response()->json(['error' => 'Not found'], 404);
    });

    Route::get('/generate-bills', [App\Http\Controllers\GenerateBillController::class, 'index'])->name('generate-bills.index')->middleware('page.permission:generate_bill,view');
    Route::get('/generate-bill', [App\Http\Controllers\GenerateBillController::class, 'create'])->name('generate-bills.create')->middleware('page.permission:generate_bill,edit');
    Route::post('/generate-bills/preview', [App\Http\Controllers\GenerateBillController::class, 'preview'])->name('generate-bills.preview');
    Route::post('/generate-bills', [App\Http\Controllers\GenerateBillController::class, 'store'])->name('generate-bills.store');
    Route::get('/generate-bills/{generateBill}/edit', [App\Http\Controllers\GenerateBillController::class, 'edit'])->name('generate-bills.edit');
    Route::put('/generate-bills/{generateBill}', [App\Http\Controllers\GenerateBillController::class, 'update'])->name('generate-bills.update');
    Route::delete('/generate-bills/{generateBill}', [App\Http\Controllers\GenerateBillController::class, 'destroy'])->name('generate-bills.destroy');
    Route::get('/generate-bills/{generateBill}/print', [App\Http\Controllers\GenerateBillController::class, 'print'])->name('generate-bills.print')->middleware('page.permission:generate_bill,view');

    Route::get('/rcvd-payment', [RcvdPaymentController::class, 'index'])->name('rcvd-payment.index')->middleware('page.permission:rcvd_payment,view');
    Route::get('/rcvd-payment/show/{rcvdPayment}', [RcvdPaymentController::class, 'show'])->name('rcvd-payment.show')->middleware('page.permission:rcvd_payment,view');
    Route::get('/rcvd-payment/create', [RcvdPaymentController::class, 'create'])->name('rcvd-payment.create')->middleware('page.permission:rcvd_payment,edit');
    Route::post('/rcvd-payment', [RcvdPaymentController::class, 'store'])->name('rcvd-payment.store')->middleware('page.permission:rcvd_payment,edit');
    Route::get('/rcvd-payment/{rcvdPayment}/edit', [RcvdPaymentController::class, 'edit'])->name('rcvd-payment.edit')->middleware('page.permission:rcvd_payment,edit');
    Route::put('/rcvd-payment/{rcvdPayment}', [RcvdPaymentController::class, 'update'])->name('rcvd-payment.update')->middleware('page.permission:rcvd_payment,edit');
    Route::delete('/rcvd-payment/{rcvdPayment}', [RcvdPaymentController::class, 'destroy'])->name('rcvd-payment.destroy')->middleware('page.permission:rcvd_payment,remove');

    Route::get('/purchase-bills', [\App\Http\Controllers\PurchaseBillController::class, 'index'])->name('purchase-bill.index')->middleware('page.permission:purchase_bill,view');
    Route::get('/purchase-bill', [\App\Http\Controllers\PurchaseBillController::class, 'create'])->name('purchase-bill.create')->middleware('page.permission:purchase_bill,edit');
    Route::post('/purchase-bills', [\App\Http\Controllers\PurchaseBillController::class, 'store'])->name('purchase-bill.store');
    Route::get('/purchase-bills/{purchaseBill}', [\App\Http\Controllers\PurchaseBillController::class, 'show'])->name('purchase-bill.show')->middleware('page.permission:purchase_bill,view');
    Route::get('/purchase-bills/{purchaseBill}/edit', [\App\Http\Controllers\PurchaseBillController::class, 'edit'])->name('purchase-bill.edit');
    Route::put('/purchase-bills/{purchaseBill}', [\App\Http\Controllers\PurchaseBillController::class, 'update'])->name('purchase-bill.update');
    Route::delete('/purchase-bills/{purchaseBill}', [\App\Http\Controllers\PurchaseBillController::class, 'destroy'])->name('purchase-bill.destroy');
    Route::get('/purchase-bills/{purchaseBill}/print', [\App\Http\Controllers\PurchaseBillController::class, 'print'])->name('purchase-bill.print')->middleware('page.permission:purchase_bill,view');

    Route::get('/generate-cheques', [\App\Http\Controllers\GeneratedChequeController::class, 'index'])->name('generate-cheques.index')->middleware('page.permission:generate_cheque,view');
    Route::get('/generate-cheque', [\App\Http\Controllers\GeneratedChequeController::class, 'create'])->name('generate-cheque')->middleware('page.permission:generate_cheque,view');
    
    Route::post('/generate-cheque', [\App\Http\Controllers\GeneratedChequeController::class, 'store'])->name('generate-cheque.store')->middleware('page.permission:generate_cheque,edit');
    Route::get('/generate-cheque/{cheque}/edit', [\App\Http\Controllers\GeneratedChequeController::class, 'edit'])->name('generate-cheque.edit')->middleware('page.permission:generate_cheque,edit');
    Route::put('/generate-cheque/{cheque}', [\App\Http\Controllers\GeneratedChequeController::class, 'update'])->name('generate-cheque.update')->middleware('page.permission:generate_cheque,edit');
    Route::get('/generate-cheque/{cheque}/show', [\App\Http\Controllers\GeneratedChequeController::class, 'show'])->name('generate-cheque.show')->middleware('page.permission:generate_cheque,view');
    Route::get('/generate-cheque/{cheque}/print', [\App\Http\Controllers\GeneratedChequeController::class, 'print'])->name('generate-cheque.print')->middleware('page.permission:generate_cheque,view');
    Route::delete('/generate-cheque/{cheque}', [\App\Http\Controllers\GeneratedChequeController::class, 'destroy'])->name('generate-cheque.destroy')->middleware('page.permission:generate_cheque,remove');

    Route::get('/bank-book', [\App\Http\Controllers\BankBookController::class, 'index'])->name('bank-book.index')->middleware('page.permission:bank_book,view');
    Route::get('/bank-book/create', [\App\Http\Controllers\BankBookController::class, 'create'])->name('bank-book.create')->middleware('page.permission:bank_book,edit');
    Route::post('/bank-book', [\App\Http\Controllers\BankBookController::class, 'store'])->name('bank-book.store')->middleware('page.permission:bank_book,edit');
    Route::get('/bank-book/{bankBook}/edit', [\App\Http\Controllers\BankBookController::class, 'edit'])->name('bank-book.edit')->middleware('page.permission:bank_book,edit');
    Route::put('/bank-book/{bankBook}', [\App\Http\Controllers\BankBookController::class, 'update'])->name('bank-book.update')->middleware('page.permission:bank_book,edit');
    Route::delete('/bank-book/{bankBook}', [\App\Http\Controllers\BankBookController::class, 'destroy'])->name('bank-book.destroy')->middleware('page.permission:bank_book,remove');

    Route::get('/thread-boxes', [\App\Http\Controllers\ThreadBoxController::class, 'index'])->name('thread-boxes.index')->middleware('page.permission:thread_boxes,view');
    Route::get('/thread-boxes/create', function () {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        $companyNames = \App\Models\ThreadBoxSetup::select('company_name')->whereNotNull('company_name')->where('company_name', '!=', '')->distinct()->pluck('company_name');
        $setups = \App\Models\ThreadBoxSetup::all()->groupBy('company_name');
        return view('thread-boxes', compact('firms', 'parties', 'companyNames', 'setups'));
    })->name('thread-boxes.create')->middleware('page.permission:thread_boxes,edit');
    Route::post('/thread-boxes', [\App\Http\Controllers\ThreadBoxController::class, 'store'])->name('thread-boxes.store')->middleware('page.permission:thread_boxes,edit');
    Route::get('/thread-boxes/{threadBox}/edit', [\App\Http\Controllers\ThreadBoxController::class, 'edit'])->name('thread-boxes.edit')->middleware('page.permission:thread_boxes,edit');
    Route::put('/thread-boxes/{threadBox}', [\App\Http\Controllers\ThreadBoxController::class, 'update'])->name('thread-boxes.update')->middleware('page.permission:thread_boxes,edit');
    Route::delete('/thread-boxes/{threadBox}', [\App\Http\Controllers\ThreadBoxController::class, 'destroy'])->name('thread-boxes.destroy')->middleware('page.permission:thread_boxes,remove');
    Route::get('/thread-boxes/{threadBox}', [\App\Http\Controllers\ThreadBoxController::class, 'show'])->name('thread-boxes.show')->middleware('page.permission:thread_boxes,view');

    Route::get('/dhaga-cuttings', [\App\Http\Controllers\DhagaCuttingController::class, 'index'])->name('dhaga-cuttings.index')->middleware('page.permission:dh_cutting,view');
    Route::get('/dhaga-cuttings/print', [\App\Http\Controllers\DhagaCuttingController::class, 'print'])->name('dhaga-cuttings.print')->middleware('page.permission:dh_cutting,view');
    Route::get('/dhaga-cuttings/create', [\App\Http\Controllers\DhagaCuttingController::class, 'create'])->name('dhaga-cuttings.create')->middleware('page.permission:dh_cutting,edit');
    Route::post('/dhaga-cuttings', [\App\Http\Controllers\DhagaCuttingController::class, 'store'])->name('dhaga-cuttings.store');
    Route::get('/dhaga-cuttings/{dhagaCutting}/edit', [\App\Http\Controllers\DhagaCuttingController::class, 'edit'])->name('dhaga-cuttings.edit');
    Route::put('/dhaga-cuttings/{dhagaCutting}', [\App\Http\Controllers\DhagaCuttingController::class, 'update'])->name('dhaga-cuttings.update');

    Route::get('/inter-exchange', [\App\Http\Controllers\InterExchangeController::class, 'index'])->name('inter-exchange.index')->middleware('page.permission:inter_exchange,view');
    Route::get('/inter-exchange/create', [\App\Http\Controllers\InterExchangeController::class, 'create'])->name('inter-exchange.create')->middleware('page.permission:inter_exchange,edit');
    Route::post('/inter-exchange', [\App\Http\Controllers\InterExchangeController::class, 'store'])->name('inter-exchange.store');
    Route::get('/inter-exchange/{interExchange}/edit', [\App\Http\Controllers\InterExchangeController::class, 'edit'])->name('inter-exchange.edit');
    Route::get('/inter-exchange/{interExchange}', [\App\Http\Controllers\InterExchangeController::class, 'show'])->name('inter-exchange.show');
    Route::put('/inter-exchange/{interExchange}', [\App\Http\Controllers\InterExchangeController::class, 'update'])->name('inter-exchange.update');
    Route::delete('/inter-exchange/{interExchange}', [\App\Http\Controllers\InterExchangeController::class, 'destroy'])->name('inter-exchange.destroy');

    // Chat System (Globally available to all users)
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/unread-count', [\App\Http\Controllers\ChatController::class, 'unreadCount'])->name('chat.unread_count');
    Route::get('/chat/messages/{userId}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');

    // Production
    Route::get('/productions/print', [\App\Http\Controllers\ProductionController::class, 'print'])->name('productions.print')->middleware('page.permission:production,view');
    Route::resource('productions', \App\Http\Controllers\ProductionController::class);

    // Settings Pages
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

    Route::prefix('settings')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('firms', FirmController::class);
        Route::resource('parties', PartyController::class);

        Route::get('thread-boxes-company', [\App\Http\Controllers\ThreadBoxSetupController::class, 'index'])->name('settings.thread-boxes-company')->middleware('page.permission:thread_boxes_setup,view');
        Route::post('thread-boxes-company', [\App\Http\Controllers\ThreadBoxSetupController::class, 'store'])->name('settings.thread-boxes-company.store')->middleware('page.permission:thread_boxes_setup,edit');
        Route::delete('thread-boxes-company/{companyName}', [\App\Http\Controllers\ThreadBoxSetupController::class, 'destroy'])->name('settings.thread-boxes-company.destroy')->middleware('page.permission:thread_boxes_setup,remove');

        Route::get('inter-exchange-company', [\App\Http\Controllers\InterExchangeSetupController::class, 'index'])->name('settings.inter-exchange-company')->middleware('page.permission:inter_exchange_setup,view');
        Route::post('inter-exchange-company', [\App\Http\Controllers\InterExchangeSetupController::class, 'store'])->name('settings.inter-exchange-company.store')->middleware('page.permission:inter_exchange_setup,edit');
        Route::delete('inter-exchange-company/{companyName}', [\App\Http\Controllers\InterExchangeSetupController::class, 'destroy'])->name('settings.inter-exchange-company.destroy')->middleware('page.permission:inter_exchange_setup,remove');

        // Logo Settings
        Route::get('logo', [LogoController::class, 'index'])->name('settings.logo')->middleware('page.permission:logo,view');
        Route::post('logo', [LogoController::class, 'update'])->name('settings.logo.update')->middleware('page.permission:logo,edit');

        // Dh. Cutting Person Settings
        Route::resource('dh-cutting-people', \App\Http\Controllers\DhCuttingPersonController::class);

        // Machine Settings
        Route::resource('machines', \App\Http\Controllers\MachineController::class);

        // Karigar Settings
        Route::resource('karigars', \App\Http\Controllers\KarigarController::class);

        // Chalan Dropdown Options
        Route::get('dropdown-options', [\App\Http\Controllers\DropdownOptionController::class, 'index'])->name('settings.dropdown-options')->middleware('page.permission:settings,view');
        Route::post('dropdown-options', [\App\Http\Controllers\DropdownOptionController::class, 'store'])->name('settings.dropdown-options.store')->middleware('page.permission:settings,edit');
        Route::delete('dropdown-options/{dropdownOption}', [\App\Http\Controllers\DropdownOptionController::class, 'destroy'])->name('settings.dropdown-options.destroy')->middleware('page.permission:settings,remove');
    });
});

use Illuminate\Support\Facades\Artisan;



Route::get('/run-migration', function () {
    Artisan::call('migrate', ['--force' => true]);
    return Artisan::output();
});