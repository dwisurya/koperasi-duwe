<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\Kas;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Writer;

class UtilityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:utility-backup', only: ['backup', 'doBackup']),
            new Middleware('permission:utility-import', only: ['import', 'doImport']),
            new Middleware('permission:utility-export', only: ['export', 'doExport']),
            new Middleware('permission:utility-log', only: ['activityLog']),
        ];
    }

    public function backup()
    {
        $backups = collect(Storage::files('backups'))
            ->filter(fn ($f) => str_ends_with($f, '.sqlite'))
            ->map(fn ($f) => [
                'name' => basename($f),
                'size' => Storage::size($f),
                'date' => Storage::lastModified($f),
            ])
            ->sortByDesc('date')
            ->values();

        return view('utility.backup', compact('backups'));
    }

    public function doBackup()
    {
        $dbPath = database_path('database.sqlite');
        if (! file_exists($dbPath)) {
            return back()->with('error', 'Database file not found.');
        }

        $filename = 'backup_'.now()->format('Y-md_His').'.sqlite';
        Storage::makeDirectory('backups');
        Storage::put('backups/'.$filename, file_get_contents($dbPath));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'backup',
            'model_type' => 'Database',
            'description' => 'Melakukan backup database: '.$filename,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Backup database berhasil: '.$filename);
    }

    public function downloadBackup(string $filename)
    {
        if (! Storage::exists('backups/'.$filename)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return Storage::download('backups/'.$filename);
    }

    public function deleteBackup(string $filename)
    {
        if (! Storage::exists('backups/'.$filename)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        Storage::delete('backups/'.$filename);

        return back()->with('success', 'Backup dihapus: '.$filename);
    }

    public function import()
    {
        $tables = ['anggota' => 'Anggota', 'simpanan' => 'Simpanan', 'pinjaman' => 'Pinjaman', 'angsuran' => 'Angsuran', 'kas' => 'Kas'];

        return view('utility.import', compact('tables'));
    }

    public function doImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
            'table' => 'required|in:anggota,simpanan,pinjaman,angsuran,kas',
        ]);

        $file = $request->file('file');
        $table = $request->input('table');
        $extension = $file->getClientOriginalExtension();

        $rows = [];
        if (in_array($extension, ['xlsx', 'xls'])) {
            $reader = new Reader;
            $reader->open($file->getRealPath());
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $rows[] = $row->toArray();
                }
                break;
            }
            $reader->close();
        } else {
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    $rows[] = $data;
                }
                fclose($handle);
            }
        }

        if (count($rows) < 2) {
            return back()->with('error', 'File kosong atau tidak memiliki data.');
        }

        $headers = array_shift($rows);
        $headers = array_map('trim', $headers);
        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $data = array_combine($headers, array_pad($row, count($headers), null));
            $data = array_filter($data, fn ($v) => ! is_null($v) && $v !== '');

            try {
                match ($table) {
                    'anggota' => Anggota::create($data),
                    'simpanan' => Simpanan::create($data),
                    'pinjaman' => Pinjaman::create($data),
                    'angsuran' => Angsuran::create($data),
                    'kas' => Kas::create($data),
                };
                $imported++;
            } catch (\Exception $e) {
                $errors[] = 'Baris '.($index + 2).': '.$e->getMessage();
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'import',
            'model_type' => 'Utility',
            'description' => "Import {$table}: {$imported} berhasil, ".count($errors).' gagal',
            'ip_address' => request()->ip(),
        ]);

        $message = "Berhasil import {$imported} data ke tabel {$table}.";
        if (! empty($errors)) {
            $message .= ' Gagal: '.implode('; ', array_slice($errors, 0, 5));
        }

        return back()->with('success', $message);
    }

    public function export()
    {
        $tables = ['anggota' => 'Anggota', 'simpanan' => 'Simpanan', 'pinjaman' => 'Pinjaman', 'angsuran' => 'Angsuran', 'kas' => 'Kas'];

        return view('utility.export', compact('tables'));
    }

    public function doExport(Request $request)
    {
        $request->validate(['table' => 'required|in:anggota,simpanan,pinjaman,angsuran,kas,user']);
        $table = $request->input('table');

        $data = match ($table) {
            'anggota' => Anggota::all(['kode', 'nama', 'nik', 'email', 'no_hp', 'tanggal_daftar']),
            'simpanan' => Simpanan::with('anggota')->get()->map(fn ($s) => [
                'anggota' => $s->anggota?->nama ?? '-', 'jenis' => $s->jenis_label, 'nominal' => $s->nominal, 'tanggal' => $s->created_at->format('d/m/Y'),
            ]),
            'pinjaman' => Pinjaman::with('anggota')->get()->map(fn ($p) => [
                'anggota' => $p->anggota?->nama ?? '-', 'nominal' => $p->nominal, 'status' => $p->status_label, 'tenor' => $p->tenor, 'tanggal' => $p->tanggal_pengajuan?->format('d/m/Y'),
            ]),
            'angsuran' => Angsuran::with('anggota', 'pinjaman')->get()->map(fn ($a) => [
                'anggota' => $a->anggota?->nama ?? '-', 'angsuran_ke' => $a->angsuran_ke, 'nominal' => $a->nominal, 'denda' => $a->denda ?? 0, 'tanggal' => $a->tanggal_bayar?->format('d/m/Y'),
            ]),
            'kas' => Kas::all(['tanggal', 'jenis', 'kategori', 'nominal', 'keterangan'])->toArray(),
            'user' => User::all(['name', 'email'])->toArray(),
            default => [],
        };

        $filename = "export_{$table}_".now()->format('Ymd_His').'.xlsx';

        $writer = new Writer;
        $writer->openToBrowser($filename);

        $headers = ! empty($data) ? array_keys($data[0]) : ['data'];
        $headerRow = Row::fromValues($headers);
        $writer->addRow($headerRow);

        foreach ($data as $row) {
            $writer->addRow(Row::fromValues(array_values($row)));
        }

        $writer->close();
        exit;
    }

    public function activityLog()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(50);

        $actions = ActivityLog::distinct('action')->pluck('action');

        return view('utility.activity-log', compact('logs', 'actions'));
    }
}
