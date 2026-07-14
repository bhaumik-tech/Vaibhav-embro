<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KarigarController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:karigars,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:karigars,remove', only: ['destroy']),
        ];
    }

    public function index()
    {
        $karigars = \App\Models\Karigar::with(['machine1', 'machine2', 'machine3'])->get();
        return view('settings.karigars.index', compact('karigars'));
    }

    public function create()
    {
        $machines = \App\Models\Machine::orderBy('machine_no')->get();
        return view('settings.karigars.create', compact('machines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'aadhar_no' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:255',
            'machine_1_id' => 'nullable|exists:machines,id',
            'machine_1_top_rs' => 'nullable|numeric',
            'machine_1_dup_rs' => 'nullable|numeric',
            'machine_2_id' => 'nullable|exists:machines,id',
            'machine_2_top_rs' => 'nullable|numeric',
            'machine_2_dup_rs' => 'nullable|numeric',
            'machine_3_id' => 'nullable|exists:machines,id',
            'machine_3_top_rs' => 'nullable|numeric',
            'machine_3_dup_rs' => 'nullable|numeric',
            'aadhar_front' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('aadhar_front')) {
            $validated['aadhar_front'] = $request->file('aadhar_front')->store('karigars', 'public');
        }
        if ($request->hasFile('aadhar_back')) {
            $validated['aadhar_back'] = $request->file('aadhar_back')->store('karigars', 'public');
        }
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('karigars', 'public');
        }

        \App\Models\Karigar::create($validated);

        return redirect()->route('karigars.index')->with('success', 'Karigar added successfully.');
    }

    public function edit(\App\Models\Karigar $karigar)
    {
        $machines = \App\Models\Machine::orderBy('machine_no')->get();
        return view('settings.karigars.edit', compact('karigar', 'machines'));
    }

    public function update(Request $request, \App\Models\Karigar $karigar)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'aadhar_no' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:255',
            'machine_1_id' => 'nullable|exists:machines,id',
            'machine_1_top_rs' => 'nullable|numeric',
            'machine_1_dup_rs' => 'nullable|numeric',
            'machine_2_id' => 'nullable|exists:machines,id',
            'machine_2_top_rs' => 'nullable|numeric',
            'machine_2_dup_rs' => 'nullable|numeric',
            'machine_3_id' => 'nullable|exists:machines,id',
            'machine_3_top_rs' => 'nullable|numeric',
            'machine_3_dup_rs' => 'nullable|numeric',
            'aadhar_front' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('aadhar_front')) {
            $validated['aadhar_front'] = $request->file('aadhar_front')->store('karigars', 'public');
        }
        if ($request->hasFile('aadhar_back')) {
            $validated['aadhar_back'] = $request->file('aadhar_back')->store('karigars', 'public');
        }
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('karigars', 'public');
        }

        $karigar->update($validated);

        return redirect()->route('karigars.index')->with('success', 'Karigar updated successfully.');
    }

    public function destroy(\App\Models\Karigar $karigar)
    {
        $karigar->delete();
        return redirect()->route('karigars.index')->with('success', 'Karigar removed successfully.');
    }

    public function show(\App\Models\Karigar $karigar)
    {
        return view('settings.karigars.show', compact('karigar'));
    }
}
