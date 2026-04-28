<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount('users')->orderBy('name')->get();
        return view('settings.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('settings.branches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean',
        ]);
        Branch::create($data);
        return redirect()->route('settings.branches.index')->with('success', 'Branch created.');
    }

    public function edit(Branch $branch)
    {
        return view('settings.branches.create', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean',
        ]);
        $branch->update($data);
        return redirect()->route('settings.branches.index')->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('settings.branches.index')->with('success', 'Branch deleted.');
    }
}
