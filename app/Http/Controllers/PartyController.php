<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PartyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:parties,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:parties,remove', only: ['destroy']),
        ];
    }

    public function index()
    {
        $parties = Party::latest()->get();
        return view('parties.index', compact('parties'));
    }

    public function create()
    {
        return view('parties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'vatav' => 'nullable|numeric|min:0',
            'sgst' => 'nullable|numeric|min:0',
            'cgst' => 'nullable|numeric|min:0',
            'tds' => 'nullable|numeric|min:0',
        ]);

        Party::create($request->all());

        return redirect()->route('parties.index')->with('success', 'Party created successfully.');
    }

    public function edit(Party $party)
    {
        return view('parties.edit', compact('party'));
    }

    public function update(Request $request, Party $party)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'vatav' => 'nullable|numeric|min:0',
            'sgst' => 'nullable|numeric|min:0',
            'cgst' => 'nullable|numeric|min:0',
            'tds' => 'nullable|numeric|min:0',
        ]);

        $party->update($request->all());

        return redirect()->route('parties.index')->with('success', 'Party updated successfully.');
    }

    public function destroy(Party $party)
    {
        $party->delete();
        return redirect()->route('parties.index')->with('success', 'Party deleted successfully.');
    }

    public function show(Party $party)
    {
        return view('parties.show', compact('party'));
    }
}
