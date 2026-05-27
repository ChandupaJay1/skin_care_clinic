<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    /**
     * Display list of all treatments
     */
    public function index(Request $request)
    {
        $query = Treatment::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $treatments = $query->latest()->paginate(10);

        return view('treatments.index', compact('treatments'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('treatments.create');
    }

    /**
     * Store new treatment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:treatments,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $treatment = Treatment::create($validated);

        return redirect()->route('treatments.show', $treatment)
            ->with('success', "Treatment \"{$treatment->name}\" created successfully!");
    }

    /**
     * Show treatment details
     */
    public function show(Treatment $treatment)
    {
        return view('treatments.show', compact('treatment'));
    }

    /**
     * Show edit form
     */
    public function edit(Treatment $treatment)
    {
        return view('treatments.edit', compact('treatment'));
    }

    /**
     * Update treatment
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:treatments,name,' . $treatment->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $treatment->update($validated);

        return redirect()->route('treatments.show', $treatment)
            ->with('success', "Treatment \"{$treatment->name}\" updated successfully!");
    }

    /**
     * Delete treatment
     */
    public function destroy(Treatment $treatment)
    {
        $name = $treatment->name;
        $treatment->delete();

        return redirect()->route('treatments.index')
            ->with('success', "Treatment \"{$name}\" deleted successfully.");
    }
}
