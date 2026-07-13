<?php

namespace App\Http\Controllers;

use App\Models\InterExchange;
use App\Models\User;
use Illuminate\Http\Request;

class InterExchangeController extends Controller
{
    public function index()
    {
        $interExchanges = InterExchange::with(['aapnarUser', 'lenarUser'])->latest('date')->get();
        return view('inter-exchanges-index', compact('interExchanges'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('inter-exchange', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_aapnar_id' => 'required|exists:users,id',
            'user_lenar_id' => 'required|exists:users,id',
            'chalan_no' => 'nullable|string',
            'date' => 'required|date',
            'remark' => 'nullable|string',
            'type_of_box' => 'array',
            'box_cone' => 'array',
            'quantity' => 'array',
            'amount' => 'array',
        ]);

        $interExchange = InterExchange::create([
            'user_aapnar_id' => $validated['user_aapnar_id'],
            'user_lenar_id' => $validated['user_lenar_id'],
            'chalan_no' => $validated['chalan_no'],
            'date' => $validated['date'],
            'remark' => $validated['remark'],
        ]);

        if (!empty($validated['type_of_box'])) {
            foreach ($validated['type_of_box'] as $index => $type) {
                if ($type) {
                    $interExchange->items()->create([
                        'type_of_box' => $type,
                        'box_cone' => $validated['box_cone'][$index] ?? null,
                        'quantity' => $validated['quantity'][$index] ?? null,
                        'amount' => $validated['amount'][$index] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('inter-exchange.index')->with('success', 'Inter-Exchange created successfully.');
    }

    public function edit(InterExchange $interExchange)
    {
        $interExchange->load('items');
        $users = User::orderBy('name')->get();
        return view('inter-exchange', compact('interExchange', 'users'));
    }

    public function update(Request $request, InterExchange $interExchange)
    {
        $validated = $request->validate([
            'user_aapnar_id' => 'required|exists:users,id',
            'user_lenar_id' => 'required|exists:users,id',
            'chalan_no' => 'nullable|string',
            'date' => 'required|date',
            'remark' => 'nullable|string',
            'type_of_box' => 'array',
            'box_cone' => 'array',
            'quantity' => 'array',
            'amount' => 'array',
        ]);

        $interExchange->update([
            'user_aapnar_id' => $validated['user_aapnar_id'],
            'user_lenar_id' => $validated['user_lenar_id'],
            'chalan_no' => $validated['chalan_no'],
            'date' => $validated['date'],
            'remark' => $validated['remark'],
        ]);

        $interExchange->items()->delete();

        if (!empty($validated['type_of_box'])) {
            foreach ($validated['type_of_box'] as $index => $type) {
                if ($type) {
                    $interExchange->items()->create([
                        'type_of_box' => $type,
                        'box_cone' => $validated['box_cone'][$index] ?? null,
                        'quantity' => $validated['quantity'][$index] ?? null,
                        'amount' => $validated['amount'][$index] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('inter-exchange.index')->with('success', 'Inter-Exchange updated successfully.');
    }

    public function destroy(InterExchange $interExchange)
    {
        $interExchange->delete();
        return redirect()->route('inter-exchange.index')->with('success', 'Inter-Exchange deleted successfully.');
    }
}
