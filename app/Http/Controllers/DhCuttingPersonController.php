<?php

namespace App\Http\Controllers;

use App\Models\DhCuttingPerson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DhCuttingPersonController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:dh_cutting,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:dh_cutting,remove', only: ['destroy']),
        ];
    }

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
