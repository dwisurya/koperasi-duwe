<?php

namespace App\Http\Controllers;

use App\Models\PersentaseShu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PersentaseShuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:persentase-shu-list|persentase-shu-create|persentase-shu-edit|persentase-shu-delete', only: ['index']),
            new Middleware('permission:persentase-shu-create', only: ['create', 'store']),
            new Middleware('permission:persentase-shu-edit', only: ['edit', 'update']),
            new Middleware('permission:persentase-shu-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $persentaseShu = PersentaseShu::orderBy('urutan')->get();

        return view('persentase-shu.index', compact('persentaseShu'));
    }

    public function create()
    {
        return view('persentase-shu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dana' => 'required|max:255',
            'persentase' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|max:1000',
            'urutan' => 'nullable|integer|min:0',
        ]);

        PersentaseShu::create($validated);

        return redirect()->route('admin.persentase-shu.index')->with('success', 'Persentase SHU berhasil ditambahkan.');
    }

    public function edit(PersentaseShu $persentaseShu)
    {
        return view('persentase-shu.edit', compact('persentaseShu'));
    }

    public function update(Request $request, PersentaseShu $persentaseShu)
    {
        $validated = $request->validate([
            'dana' => 'required|max:255',
            'persentase' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|max:1000',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $persentaseShu->update($validated);

        return redirect()->route('admin.persentase-shu.index')->with('success', 'Persentase SHU berhasil diperbarui.');
    }

    public function destroy(PersentaseShu $persentaseShu)
    {
        $persentaseShu->delete();

        return redirect()->route('admin.persentase-shu.index')->with('success', 'Persentase SHU berhasil dihapus.');
    }
}
