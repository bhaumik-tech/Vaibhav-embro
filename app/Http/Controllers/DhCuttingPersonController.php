<?php

namespace App\Http\Controllers;

use App\Models\DhCuttingPerson;
use Illuminate\Http\Request;

class DhCuttingPersonController extends Controller
{
    public function index()
    {
        $people = DhCuttingPerson::orderBy('person_name')->get();
        return view('settings.dh-cutting-people.index', compact('people'));
    }

    public function create()
    {
        return view('settings.dh-cutting-people.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'person_name' => 'required|string|max:255',
        ]);

        DhCuttingPerson::create($request->all());

        return redirect()->route('dh-cutting-people.index')->with('success', 'Person created successfully.');
    }

    public function edit(DhCuttingPerson $dhCuttingPerson)
    {
        return view('settings.dh-cutting-people.edit', compact('dhCuttingPerson'));
    }

    public function update(Request $request, DhCuttingPerson $dhCuttingPerson)
    {
        $request->validate([
            'person_name' => 'required|string|max:255',
        ]);

        $dhCuttingPerson->update($request->all());

        return redirect()->route('dh-cutting-people.index')->with('success', 'Person updated successfully.');
    }

    public function destroy(DhCuttingPerson $dhCuttingPerson)
    {
        $dhCuttingPerson->delete();
        return redirect()->route('dh-cutting-people.index')->with('success', 'Person deleted successfully.');
    }

    public function show(DhCuttingPerson $dhCuttingPerson)
    {
        return view('settings.dh-cutting-people.show', compact('dhCuttingPerson'));
    }
}
