<?php

namespace App\Http\Controllers;

use App\Models\InterExchange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class InterExchangeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:inter_exchange,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:inter_exchange,remove', only: ['destroy']),
        ];
    }

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
            'photo' => 'nullable|image|max:10240', // 10MB max
        ]);

        $data = [
            'user_aapnar_id' => $validated['user_aapnar_id'],
            'user_lenar_id' => $validated['user_lenar_id'],
            'chalan_no' => $validated['chalan_no'],
            'date' => $validated['date'],
            'remark' => $validated['remark'],
        ];

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('inter_exchange_photos', 'public');
        }

        $interExchange = InterExchange::create($data);

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
            'photo' => 'nullable|image|max:10240',
        ]);

        $data = [
            'user_aapnar_id' => $validated['user_aapnar_id'],
            'user_lenar_id' => $validated['user_lenar_id'],
            'chalan_no' => $validated['chalan_no'],
            'date' => $validated['date'],
            'remark' => $validated['remark'],
        ];

        if ($request->hasFile('photo')) {
            if ($interExchange->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($interExchange->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('inter_exchange_photos', 'public');
        }

        $interExchange->update($data);

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
        if ($interExchange->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($interExchange->photo_path);
        }
        $interExchange->delete();
        return redirect()->route('inter-exchange.index')->with('success', 'Inter-Exchange deleted successfully.');
    }
}
