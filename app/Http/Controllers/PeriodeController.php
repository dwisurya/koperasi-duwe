<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PeriodeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:periode-list|periode-create|periode-edit|periode-delete', only: ['index']),
            new Middleware('permission:periode-create', only: ['create', 'store']),
            new Middleware('permission:periode-edit', only: ['edit', 'update']),
            new Middleware('permission:periode-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $periodes = Periode::latest()->get();

        return view('periodes.index', compact('periodes'));
    }

    public function create()
    {
        return view('periodes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|digits:4|integer|min:2000|max:2099',
            'nama' => 'nullable|max:255',
            'is_active' => 'boolean',
        ]);

        if ($request->boolean('is_active')) {
            Periode::where('is_active', true)->update(['is_active' => false]);
        }

        Periode::create($validated);

        return redirect()->route('admin.periodes.index')->with('success', 'Periode created successfully.');
    }

    public function edit(Periode $periode)
    {
        return view('periodes.edit', compact('periode'));
    }

    public function update(Request $request, Periode $periode)
    {
        $validated = $request->validate([
            'tahun' => 'required|digits:4|integer|min:2000|max:2099',
            'nama' => 'nullable|max:255',
            'is_active' => 'boolean',
        ]);

        if ($request->boolean('is_active')) {
            Periode::where('is_active', true)->where('id', '!=', $periode->id)->update(['is_active' => false]);
        }

        $periode->update($validated);

        return redirect()->route('admin.periodes.index')->with('success', 'Periode updated successfully.');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->is_active) {
            return back()->with('error', 'Cannot delete active period. Set another period as active first.');
        }

        $periode->delete();

        return redirect()->route('admin.periodes.index')->with('success', 'Periode deleted successfully.');
    }

    public function activate(Periode $periode)
    {
        Periode::where('is_active', true)->update(['is_active' => false]);
        $periode->update(['is_active' => true]);

        return redirect()->route('admin.periodes.index')->with('success', "Periode {$periode->tahun} is now active.");
    }
}
