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

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $firms = \App\Models\Firm::getPermitted()->load('machines');
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('dashboard', compact('firms', 'parties'));
    })->middleware('page.permission:dashboard,view');

    Route::get('/register', function () {
        $parties = \App\Models\Party::orderBy('name')->get();
        $query = \App\Models\InputChalan::with(['items', 'firm']);
        $outQuery = \App\Models\OutputChalan::with(['firm']);
        if (request('party_id')) {
            $query->where('party_id', request('party_id'));
            $outQuery->where('party_id', request('party_id'));
        }

        $status = request('status', 'pending');
        if ($status === 'done') {
            $query->where('is_done', 1);
            $outQuery->where('is_done', 1);
        } else {
            $query->where('is_done', 0);
            $outQuery->where('is_done', 0);
        }

        $outputChalans = clone $outQuery;
        $outputChalans = $outputChalans->latest('date')->get()->map(function ($ch) {
            $ch->source_type = 'output';
            return $ch;
        });

        $genQuery = \App\Models\GenerateChalan::with(['firm', 'items']);
        if (request('party_id')) {
            $genQuery->where('party_id', request('party_id'));
        }
        if ($status === 'done') {
            $genQuery->where('is_done', 1);
        } else {
            $genQuery->where('is_done', 0);
        }
        $genChalans = $genQuery->latest('date')->get()->map(function ($ch) {
            $ch->source_type = 'generate';
            $ch->total_pcs = $ch->items->sum('pcs');
            $ch->total_amount = $ch->items->sum('amount');
            $ch->party_chalan_no = $ch->party_ch;
            return $ch;
        });

        $mergedOutputs = collect()->concat($outputChalans)->concat($genChalans)->sortByDesc('date');
        $inputChalans = $query->latest('date')->get();
        $outputChalans = $mergedOutputs;

        $firms = \App\Models\Firm::getPermitted();

        return view('register', compact('parties', 'inputChalans', 'outputChalans', 'firms'));
    })->name('register.index')->middleware('page.permission:registers,view');

    Route::get('/register/print', function () {
        $parties = \App\Models\Party::orderBy('name')->get();
        $query = \App\Models\InputChalan::with(['items', 'firm']);
        $outQuery = \App\Models\OutputChalan::with(['firm']);
        if (request('party_id')) {
            $query->where('party_id', request('party_id'));
            $outQuery->where('party_id', request('party_id'));
        }

        $status = request('status', 'pending');
        if ($status === 'done') {
            $query->where('is_done', 1);
            $outQuery->where('is_done', 1);
        } else {
            $query->where('is_done', 0);
            $outQuery->where('is_done', 0);
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
        if ($status === 'done') {
            $genQuery->where('is_done', 1);
        } else {
            $genQuery->where('is_done', 0);
        }
        $genChalans = $genQuery->oldest('date')->get()->map(function ($ch) {
            $ch->source_type = 'generate';
            $ch->total_pcs = clone $ch->items()->sum('pcs');
            $ch->total_pcs = $ch->items->sum('pcs');
            $ch->total_amount = $ch->items->sum('amount');
            $ch->party_chalan_no = $ch->party_ch;
            return $ch;
        });

        $mergedOutputs = collect()->concat($outputChalans)->concat($genChalans)->sortBy('date');

        $inputChalans = $query->oldest('date')->get();
        $outputChalans = $mergedOutputs;

        $party = request('party_id') ? \App\Models\Party::find(request('party_id')) : null;

        return view('register-print', compact('inputChalans', 'outputChalans', 'party'));
    })->name('register.print')->middleware('page.permission:registers,view');

    Route::get('/input-chalan', function () {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('input-chalan', compact('firms', 'parties'));
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
    Route::post('/generate-chalans', [App\Http\Controllers\GenerateChalanController::class, 'store'])->name('generate-chalans.store');
    Route::get('/generate-chalans/{generateChalan}/edit', [App\Http\Controllers\GenerateChalanController::class, 'edit'])->name('generate-chalans.edit');
    Route::put('/generate-chalans/{generateChalan}', [App\Http\Controllers\GenerateChalanController::class, 'update'])->name('generate-chalans.update');
    Route::delete('/generate-chalans/{generateChalan}', [App\Http\Controllers\GenerateChalanController::class, 'destroy'])->name('generate-chalans.destroy');
    Route::post('/generate-chalans/{generateChalan}/toggle-done', function (\App\Models\GenerateChalan $generateChalan) {
        $generateChalan->is_done = !$generateChalan->is_done;
        $generateChalan->save();
        return back()->with('success', 'Generate Chalan status updated!');
    })->name('generate-chalans.toggle-done')->middleware('page.permission:generate_chalan,edit');
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

    Route::get('/generate-bills', [App\Http\Controllers\GenerateBillController::class, 'index'])->name('generate-bills.index')->middleware('page.permission:generate_bill,view');
    Route::get('/generate-bill', [App\Http\Controllers\GenerateBillController::class, 'create'])->name('generate-bills.create')->middleware('page.permission:generate_bill,edit');
    Route::post('/generate-bills', [App\Http\Controllers\GenerateBillController::class, 'store'])->name('generate-bills.store');
    Route::get('/generate-bills/{generateBill}/edit', [App\Http\Controllers\GenerateBillController::class, 'edit'])->name('generate-bills.edit');
    Route::put('/generate-bills/{generateBill}', [App\Http\Controllers\GenerateBillController::class, 'update'])->name('generate-bills.update');
    Route::delete('/generate-bills/{generateBill}', [App\Http\Controllers\GenerateBillController::class, 'destroy'])->name('generate-bills.destroy');
    Route::get('/generate-bills/{generateBill}/print', [App\Http\Controllers\GenerateBillController::class, 'print'])->name('generate-bills.print')->middleware('page.permission:generate_bill,view');

    Route::get('/rcvd-payment', [RcvdPaymentController::class, 'index'])->name('rcvd-payment.index')->middleware('page.permission:rcvd_payment,view');
    Route::get('/rcvd-payment/create', [RcvdPaymentController::class, 'create'])->name('rcvd-payment.create')->middleware('page.permission:rcvd_payment,edit');
    Route::post('/rcvd-payment', [RcvdPaymentController::class, 'store'])->name('rcvd-payment.store')->middleware('page.permission:rcvd_payment,edit');
    Route::get('/rcvd-payment/{rcvdPayment}/edit', [RcvdPaymentController::class, 'edit'])->name('rcvd-payment.edit')->middleware('page.permission:rcvd_payment,edit');
    Route::put('/rcvd-payment/{rcvdPayment}', [RcvdPaymentController::class, 'update'])->name('rcvd-payment.update')->middleware('page.permission:rcvd_payment,edit');
    Route::delete('/rcvd-payment/{rcvdPayment}', [RcvdPaymentController::class, 'destroy'])->name('rcvd-payment.destroy')->middleware('page.permission:rcvd_payment,remove');

    Route::get('/purchase-bills', [\App\Http\Controllers\PurchaseBillController::class, 'index'])->name('purchase-bill.index')->middleware('page.permission:purchase_bill,view');
    Route::get('/purchase-bill', [\App\Http\Controllers\PurchaseBillController::class, 'create'])->name('purchase-bill.create')->middleware('page.permission:purchase_bill,edit');
    Route::post('/purchase-bills', [\App\Http\Controllers\PurchaseBillController::class, 'store'])->name('purchase-bill.store');
    Route::get('/purchase-bills/{purchaseBill}/edit', [\App\Http\Controllers\PurchaseBillController::class, 'edit'])->name('purchase-bill.edit');
    Route::put('/purchase-bills/{purchaseBill}', [\App\Http\Controllers\PurchaseBillController::class, 'update'])->name('purchase-bill.update');
    Route::delete('/purchase-bills/{purchaseBill}', [\App\Http\Controllers\PurchaseBillController::class, 'destroy'])->name('purchase-bill.destroy');
    Route::get('/purchase-bills/{purchaseBill}/print', [\App\Http\Controllers\PurchaseBillController::class, 'print'])->name('purchase-bill.print')->middleware('page.permission:purchase_bill,view');

    Route::get('/generate-cheque', function () {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('generate-cheque', compact('firms', 'parties'));
    })->middleware('page.permission:generate_cheque,view');

    Route::get('/bank-book', [\App\Http\Controllers\BankBookController::class, 'index'])->name('bank-book.index')->middleware('page.permission:bank_book,view');
    Route::post('/bank-book', [\App\Http\Controllers\BankBookController::class, 'store'])->name('bank-book.store');
    Route::delete('/bank-book/{bankBook}', [\App\Http\Controllers\BankBookController::class, 'destroy'])->name('bank-book.destroy');

    Route::get('/thread-boxes', function () {
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        return view('thread-boxes', compact('firms', 'parties'));
    })->middleware('page.permission:thread_boxes,view');

    Route::get('/dhaga-cuttings', [\App\Http\Controllers\DhagaCuttingController::class, 'index'])->name('dhaga-cuttings.index')->middleware('page.permission:dh_cutting,view');
    Route::get('/dhaga-cuttings/create', [\App\Http\Controllers\DhagaCuttingController::class, 'create'])->name('dhaga-cuttings.create')->middleware('page.permission:dh_cutting,edit');
    Route::post('/dhaga-cuttings', [\App\Http\Controllers\DhagaCuttingController::class, 'store'])->name('dhaga-cuttings.store');
    Route::get('/dhaga-cuttings/{dhagaCutting}/edit', [\App\Http\Controllers\DhagaCuttingController::class, 'edit'])->name('dhaga-cuttings.edit');
    Route::put('/dhaga-cuttings/{dhagaCutting}', [\App\Http\Controllers\DhagaCuttingController::class, 'update'])->name('dhaga-cuttings.update');

    Route::get('/inter-exchange', [\App\Http\Controllers\InterExchangeController::class, 'index'])->name('inter-exchange.index')->middleware('page.permission:inter_exchange,view');
    Route::get('/inter-exchange/create', [\App\Http\Controllers\InterExchangeController::class, 'create'])->name('inter-exchange.create')->middleware('page.permission:inter_exchange,edit');
    Route::post('/inter-exchange', [\App\Http\Controllers\InterExchangeController::class, 'store'])->name('inter-exchange.store');
    Route::get('/inter-exchange/{interExchange}/edit', [\App\Http\Controllers\InterExchangeController::class, 'edit'])->name('inter-exchange.edit');
    Route::put('/inter-exchange/{interExchange}', [\App\Http\Controllers\InterExchangeController::class, 'update'])->name('inter-exchange.update');
    Route::delete('/inter-exchange/{interExchange}', [\App\Http\Controllers\InterExchangeController::class, 'destroy'])->name('inter-exchange.destroy');

    // Chat System (Globally available to all users)
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/unread-count', [\App\Http\Controllers\ChatController::class, 'unreadCount'])->name('chat.unread_count');
    Route::get('/chat/messages/{userId}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');

    // Production
    Route::resource('productions', \App\Http\Controllers\ProductionController::class)->middleware('page.permission:production,edit');

    // Settings Pages
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

    Route::prefix('settings')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('firms', FirmController::class);
        Route::resource('parties', PartyController::class);

        Route::get('thread-boxes-company', [\App\Http\Controllers\ThreadBoxSetupController::class, 'index'])->name('settings.thread-boxes-company')->middleware('page.permission:thread_boxes,view');
        Route::post('thread-boxes-company', [\App\Http\Controllers\ThreadBoxSetupController::class, 'store'])->name('settings.thread-boxes-company.store');
        Route::delete('thread-boxes-company/{companyName}', [\App\Http\Controllers\ThreadBoxSetupController::class, 'destroy'])->name('settings.thread-boxes-company.destroy');

        Route::get('inter-exchange-company', [\App\Http\Controllers\InterExchangeSetupController::class, 'index'])->name('settings.inter-exchange-company')->middleware('page.permission:inter_exchange,view');
        Route::post('inter-exchange-company', [\App\Http\Controllers\InterExchangeSetupController::class, 'store'])->name('settings.inter-exchange-company.store');
        Route::delete('inter-exchange-company/{companyName}', [\App\Http\Controllers\InterExchangeSetupController::class, 'destroy'])->name('settings.inter-exchange-company.destroy');

        // Logo Settings
        Route::get('logo', [LogoController::class, 'index'])->name('settings.logo');
        Route::post('logo', [LogoController::class, 'update'])->name('settings.logo.update');

        // Dh. Cutting Person Settings
        Route::resource('dh-cutting-people', \App\Http\Controllers\DhCuttingPersonController::class);

        // Machine Settings
        Route::resource('machines', \App\Http\Controllers\MachineController::class);

        // Karigar Settings
        Route::resource('karigars', \App\Http\Controllers\KarigarController::class);
    });
});
