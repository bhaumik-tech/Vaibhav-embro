<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThreadBox;
use App\Models\ThreadBoxItem;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ThreadBoxController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:thread_boxes,view', only: ['index', 'show', 'print']),
            new Middleware('page.permission:thread_boxes,edit', only: ['create', 'store', 'edit', 'update']),
            new Middleware('page.permission:thread_boxes,remove', only: ['destroy']),
        ];
    }

    public function index()
    {
        $threadBoxes = ThreadBox::with('items')->latest('date')->get();
        return view('thread-boxes-index', compact('threadBoxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'ch_no' => 'nullable|string',
            'date' => 'required|date',
            'remark' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'items' => 'required|array|min:1',
            'items.*.type_of_box' => 'nullable|string',
            'items.*.box_cone' => 'nullable|string',
            'items.*.quantity' => 'nullable|numeric',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('thread_boxes', 'public');
        }

        $threadBox = ThreadBox::create([
            'company_name' => $request->company_name,
            'ch_no' => $request->ch_no,
            'date' => $request->date,
            'remark' => $request->remark,
            'is_highlighted' => $request->boolean('is_highlighted', false),
            'image_path' => $imagePath,
        ]);

        foreach ($request->items as $item) {
            if (empty($item['type_of_box']) && empty($item['box_cone']) && empty($item['quantity'])) {
                continue;
            }
            $threadBox->items()->create([
                'type_of_box' => $item['type_of_box'] ?? null,
                'box_cone' => $item['box_cone'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'is_highlighted' => !empty($item['is_highlighted']),
                'highlight_color' => $item['highlight_color'] ?? null,
            ]);
        }

        return redirect()->route('thread-boxes.index')->with('success', 'Thread Box Entry saved successfully.');
    }

    public function edit(ThreadBox $threadBox)
    {
        $threadBox->load('items');
        $firms = \App\Models\Firm::getPermitted();
        $parties = \App\Models\Party::orderBy('name')->get();
        $companyNames = \App\Models\ThreadBoxSetup::select('company_name')->whereNotNull('company_name')->where('company_name', '!=', '')->distinct()->pluck('company_name');
        
        // Needed for dropdowns of type_of_box if they select a different company
        $setups = \App\Models\ThreadBoxSetup::all()->groupBy('company_name');
        
        return view('thread-boxes', compact('firms', 'parties', 'companyNames', 'setups', 'threadBox'));
    }

    public function update(Request $request, ThreadBox $threadBox)
    {
        $request->validate([
            'company_name' => 'required|string',
            'ch_no' => 'nullable|string',
            'date' => 'required|date',
            'remark' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'items' => 'required|array|min:1',
            'items.*.type_of_box' => 'nullable|string',
            'items.*.box_cone' => 'nullable|string',
            'items.*.quantity' => 'nullable|numeric',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'ch_no' => $request->ch_no,
            'date' => $request->date,
            'remark' => $request->remark,
            'is_highlighted' => $request->boolean('is_highlighted', false),
        ];

        if ($request->hasFile('image')) {
            if ($threadBox->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($threadBox->image_path);
            }
            $data['image_path'] = $request->file('image')->store('thread_boxes', 'public');
        }

        $threadBox->update($data);

        $threadBox->items()->delete();

        foreach ($request->items as $item) {
            if (empty($item['type_of_box']) && empty($item['box_cone']) && empty($item['quantity'])) {
                continue;
            }
            $threadBox->items()->create([
                'type_of_box' => $item['type_of_box'] ?? null,
                'box_cone' => $item['box_cone'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'is_highlighted' => !empty($item['is_highlighted']),
                'highlight_color' => $item['highlight_color'] ?? null,
            ]);
        }

        return redirect()->route('thread-boxes.index')->with('success', 'Thread Box Entry updated successfully.');
    }

    public function destroy(ThreadBox $threadBox)
    {
        $threadBox->delete();
        return redirect()->route('thread-boxes.index')->with('success', 'Entry deleted successfully.');
    }

    public function show(ThreadBox $threadBox)
    {
        $threadBox->load('items');
        return view('thread-boxes-show', compact('threadBox'));
    }
}
