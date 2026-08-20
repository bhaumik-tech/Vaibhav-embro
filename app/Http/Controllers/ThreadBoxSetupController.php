<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\ThreadBoxSetup;
use App\Models\Firm;

class ThreadBoxSetupController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:thread_boxes,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:thread_boxes,remove', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $companyName = $request->query('company_name');
        $action = $request->query('action');
        
        $boxTypes = ThreadBoxSetup::select('type_of_box')->whereNotNull('type_of_box')->where('type_of_box', '!=', '')->distinct()->pluck('type_of_box');
        $companyNames = ThreadBoxSetup::select('company_name')->whereNotNull('company_name')->where('company_name', '!=', '')->distinct()->pluck('company_name');

        $companies = ThreadBoxSetup::select('company_name', 'second_name', 'gst_number', 'address')
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->distinct()
            ->get();

        // If explicitly editing/viewing a company or creating a new one
        if ($companyName || $action === 'create') {
            $setups = collect();
            if ($companyName) {
                $setups = ThreadBoxSetup::where('company_name', $companyName)->get();
            }
            return view('settings.thread-boxes-company', compact('companyNames', 'setups', 'companyName', 'boxTypes'));
        }
        
        return view('settings.thread-boxes-company-index', compact('companyNames', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'second_name' => 'nullable|string',
            'gst_number' => 'nullable|string',
            'address' => 'nullable|string',
            'type_of_box' => 'array',
            'box_cone' => 'array',
            'rate' => 'array'
        ]);

        $companyName = $request->company_name;
        
        // Clear old setups for this firm
        ThreadBoxSetup::where('company_name', $companyName)->delete();

        if ($request->has('type_of_box')) {
            foreach ($request->type_of_box as $index => $type) {
                if (!empty($type)) {
                    ThreadBoxSetup::create([
                        'company_name' => $companyName,
                        'second_name' => $request->second_name,
                        'gst_number' => $request->gst_number,
                        'address' => $request->address,
                        'type_of_box' => $type,
                        'box_cone' => $request->box_cone[$index] ?? '',
                        'rate' => $request->rate[$index] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('settings.thread-boxes-company', ['company_name' => $companyName])->with('success', 'Thread Box Setup saved successfully.');
    }

    public function destroy($companyName)
    {
        ThreadBoxSetup::where('company_name', $companyName)->delete();
        return redirect()->route('settings.thread-boxes-company')->with('success', 'Company removed successfully.');
    }
}
