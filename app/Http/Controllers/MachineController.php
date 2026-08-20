<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MachineController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:machines,view', only: ['index', 'show']),
            new Middleware('page.permission:machines,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:machines,remove', only: ['destroy']),
        ];
    }

    public function index()
    {
        $machines = Machine::with('firm')->orderBy('machine_no')->get();
        return view('settings.machines.index', compact('machines'));
    }

    public function create()
    {
        $firms = Firm::getPermitted();
        return view('settings.machines.create', compact('firms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'machine_no' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'no_of_head' => 'nullable|integer',
            'area' => 'nullable|string|max:255',
            'top_dup' => 'nullable|string|max:255',
        ]);

        Machine::create([
            'firm_id' => $request->firm_id,
            'machine_no' => $request->machine_no,
            'place' => $request->place,
            'no_of_head' => $request->no_of_head,
            'area' => $request->area,
            'top_dup' => $request->top_dup,
            'bonus_production_enabled' => $request->has('bonus_production_enabled'),
            'bonus_production_value' => $request->bonus_production_value,
            'bonus_frame_enabled' => $request->has('bonus_frame_enabled'),
            'bonus_frame_value' => $request->bonus_frame_value,
        ]);

        return redirect()->route('machines.index')->with('success', 'Machine added successfully.');
    }

    public function edit(Machine $machine)
    {
        $firms = Firm::getPermitted();
        return view('settings.machines.edit', compact('machine', 'firms'));
    }

    public function update(Request $request, Machine $machine)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'machine_no' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'no_of_head' => 'nullable|integer',
            'area' => 'nullable|string|max:255',
            'top_dup' => 'nullable|string|max:255',
        ]);

        $machine->update([
            'firm_id' => $request->firm_id,
            'machine_no' => $request->machine_no,
            'place' => $request->place,
            'no_of_head' => $request->no_of_head,
            'area' => $request->area,
            'top_dup' => $request->top_dup,
            'bonus_production_enabled' => $request->has('bonus_production_enabled'),
            'bonus_production_value' => $request->bonus_production_value,
            'bonus_frame_enabled' => $request->has('bonus_frame_enabled'),
            'bonus_frame_value' => $request->bonus_frame_value,
        ]);

        return redirect()->route('machines.index')->with('success', 'Machine updated successfully.');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('machines.index')->with('success', 'Machine removed successfully.');
    }

    public function show(Machine $machine)
    {
        return view('settings.machines.show', compact('machine'));
    }
}
