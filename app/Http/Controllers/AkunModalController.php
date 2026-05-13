<?php

namespace App\Http\Controllers;

use App\Models\AkunModal;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AkunModalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:akun-modal-list|akun-modal-create|akun-modal-edit|akun-modal-delete', only: ['index']),
            new Middleware('permission:akun-modal-create', only: ['create', 'store']),
            new Middleware('permission:akun-modal-edit', only: ['edit', 'update']),
            new Middleware('permission:akun-modal-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $akunModal = AkunModal::latest()->get();

        return view('akun-modal.index', compact('akunModal'));
    }

    public function create()
    {
        return view('akun-modal.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|max:50|unique:akun_modal,kode',
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:1000',
        ]);

        AkunModal::create($validated);

        return redirect()->route('admin.akun-modal.index')->with('success', 'Akun modal berhasil ditambahkan.');
    }

    public function edit(AkunModal $akunModal)
    {
        return view('akun-modal.edit', compact('akunModal'));
    }

    public function update(Request $request, AkunModal $akunModal)
    {
        $validated = $request->validate([
            'kode' => 'required|max:50|unique:akun_modal,kode,'.$akunModal->id,
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:1000',
        ]);

        $akunModal->update($validated);

        return redirect()->route('admin.akun-modal.index')->with('success', 'Akun modal berhasil diperbarui.');
    }

    public function destroy(AkunModal $akunModal)
    {
        $akunModal->delete();

        return redirect()->route('admin.akun-modal.index')->with('success', 'Akun modal berhasil dihapus.');
    }
}
