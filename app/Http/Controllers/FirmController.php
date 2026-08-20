<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FirmController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:firms,view', only: ['index', 'show']),
            new Middleware('page.permission:firms,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:firms,remove', only: ['destroy']),
        ];
    }

    public function index()
    {
        $firms = Firm::getPermitted();
        return view('firms.index', compact('firms'));
    }

    public function create()
    {
        return view('firms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:100',
        ]);

        Firm::create($request->all());

        return redirect()->route('firms.index')->with('success', 'Firm created successfully.');
    }

    public function edit(Firm $firm)
    {
        return view('firms.edit', compact('firm'));
    }

    public function update(Request $request, Firm $firm)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:100',
        ]);

        $firm->update($request->all());

        return redirect()->route('firms.index')->with('success', 'Firm updated successfully.');
    }

    public function destroy(Firm $firm)
    {
        $firm->delete();
        return redirect()->route('firms.index')->with('success', 'Firm deleted successfully.');
    }

    public function show(Firm $firm)
    {
        return view('firms.show', compact('firm'));
    }
}
