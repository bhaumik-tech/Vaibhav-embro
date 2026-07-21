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

    public function index(Request $request)
    {
        $firms = \App\Models\Firm::getPermitted();
        $permittedFirmIds = $firms->pluck('id')->toArray();
        
        $query = Party::with('firms')->latest();
        
        if (!auth()->user()->isAdmin()) {
            $query->whereHas('firms', function($q) use ($permittedFirmIds) {
                $q->whereIn('firms.id', $permittedFirmIds);
            });
        }
        
        if ($request->filled('firm_id') && in_array($request->firm_id, $permittedFirmIds)) {
            $query->whereHas('firms', function($q) use ($request) {
                $q->where('firms.id', $request->firm_id);
            });
        }
        
        $parties = $query->get();
        return view('parties.index', compact('parties', 'firms'));
    }

    public function create()
    {
        $firms = \App\Models\Firm::getPermitted();
        return view('parties.create', compact('firms'));
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
            'firm_ids' => 'nullable|array',
            'firm_ids.*' => 'exists:firms,id',
        ]);

        $party = Party::create($request->except('firm_ids'));
        
        if ($request->has('firm_ids')) {
            $party->firms()->sync($request->firm_ids);
        }

        return redirect()->route('parties.index')->with('success', 'Party created successfully.');
    }

    public function edit(Party $party)
    {
        $firms = \App\Models\Firm::getPermitted();
        return view('parties.edit', compact('party', 'firms'));
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
            'firm_ids' => 'nullable|array',
            'firm_ids.*' => 'exists:firms,id',
        ]);

        $party->update($request->except('firm_ids'));
        
        if ($request->has('firm_ids')) {
            $party->firms()->sync($request->firm_ids);
        } else {
            $party->firms()->sync([]);
        }

        return redirect()->route('parties.index')->with('success', 'Party updated successfully.');
    }

    public function destroy(Party $party)
    {
        $party->delete();
        return redirect()->route('parties.index')->with('success', 'Party deleted successfully.');
    }

    public function show(Request $request, Party $party)
    {
        $firms = \App\Models\Firm::getPermitted();
        $permittedFirmIds = $firms->pluck('id')->toArray();
        
        $selectedFirmId = $request->input('firm_id');
        if ($selectedFirmId && in_array($selectedFirmId, $permittedFirmIds)) {
            $firmIdsToFilter = [$selectedFirmId];
        } else {
            $firmIdsToFilter = $permittedFirmIds;
        }

        return view('parties.show', compact('party', 'firms', 'selectedFirmId', 'firmIdsToFilter'));
    }
}
