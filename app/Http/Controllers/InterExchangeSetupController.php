<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterExchangeSetup;
use App\Models\Firm;

class InterExchangeSetupController extends Controller
{
    public function index(Request $request)
    {
        $companyName = $request->query('company_name');
        $action = $request->query('action');
        
        $boxTypes = InterExchangeSetup::select('type_of_box')->whereNotNull('type_of_box')->where('type_of_box', '!=', '')->distinct()->pluck('type_of_box');
        $companyNames = InterExchangeSetup::select('company_name')->whereNotNull('company_name')->where('company_name', '!=', '')->distinct()->pluck('company_name');
        
        $firms = Firm::orderBy('name')->get();

        // If explicitly editing/viewing a company or creating a new one
        if ($companyName || $action === 'create') {
            $setups = collect();
            if ($companyName) {
                $setups = InterExchangeSetup::where('company_name', $companyName)->get();
            }
            return view('settings.inter-exchange-company', compact('companyNames', 'setups', 'companyName', 'boxTypes', 'firms'));
        }
        
        return view('settings.inter-exchange-company-index', compact('companyNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'type_of_box' => 'array',
            'box_cone' => 'array',
            'rate' => 'array'
        ]);

        $companyName = $request->company_name;
        
        // Clear old setups for this firm
        InterExchangeSetup::where('company_name', $companyName)->delete();

        if ($request->has('type_of_box')) {
            foreach ($request->type_of_box as $index => $type) {
                if (!empty($type)) {
                    InterExchangeSetup::create([
                        'company_name' => $companyName,
                        'type_of_box' => $type,
                        'box_cone' => $request->box_cone[$index] ?? '',
                        'rate' => $request->rate[$index] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('settings.inter-exchange-company', ['company_name' => $companyName])->with('success', 'Inter-Exchange Setup saved successfully.');
    }

    public function destroy($companyName)
    {
        InterExchangeSetup::where('company_name', $companyName)->delete();
        return redirect()->route('settings.inter-exchange-company')->with('success', 'Company removed successfully.');
    }
}
