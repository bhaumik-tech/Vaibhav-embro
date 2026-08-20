<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DropdownOption;

class DropdownOptionController extends Controller
{
    public function index()
    {
        $options = DropdownOption::with('children')->whereNull('parent_id')->orderBy('column_name')->orderBy('value')->get()->groupBy('column_name');
        return view('settings.dropdown-options', compact('options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'column_name' => 'required|string',
            'value' => 'required|string',
            'parent_id' => 'nullable|exists:dropdown_options,id'
        ]);

        $option = DropdownOption::firstOrCreate([
            'column_name' => $request->column_name,
            'value' => $request->value,
            'parent_id' => $request->parent_id ?? null
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'option' => $option]);
        }

        return back()->with('success', 'Option added successfully.');
    }

    public function destroy(DropdownOption $dropdownOption)
    {
        $dropdownOption->delete();
        return back()->with('success', 'Option removed successfully.');
    }
}
