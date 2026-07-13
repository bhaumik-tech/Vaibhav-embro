<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogoController extends Controller
{
    public function index()
    {
        return view('settings.logo');
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            // Save as logo.png in public directory, overwriting any existing
            $image->move(public_path('/'), 'logo.png');
            
            return redirect()->route('settings.logo')->with('success', 'Logo updated successfully! Please hard refresh (Ctrl+F5) to see the new favicon and logo.');
        }

        return back()->with('error', 'Failed to upload logo.');
    }
}
