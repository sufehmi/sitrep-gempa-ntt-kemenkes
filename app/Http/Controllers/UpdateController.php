<?php

namespace App\Http\Controllers;

use App\Models\AnalisaRingkasan;
use App\Models\Kabupaten;
use App\Models\KondisiPasienPuskesmas;
use App\Models\KondisiPasienRs;
use App\Models\SituasiKesehatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UpdateController extends Controller
{
    private const MANAGE_USER_SHA1 = '8e4b4051c65d8e56b261860e5af16e4b2b8f74b8';

    private const TABLES = [
        'analisa'  => 'Analisa Ringkasan',
        'situasi'  => 'Situasi Kesehatan',
        'rs'       => 'Kondisi Pasien RS',
        'puskesmas'=> 'Kondisi Pasien Puskesmas',
    ];

    public function gate(): View
    {
        return view('update.gate');
    }

    public function verifyGate(Request $request)
    {
        $request->validate([
            'manage_password' => ['required', 'string', 'regex:/^[a-f0-9]{40}$/i'],
        ], [
            'manage_password.regex' => 'Password Update harus berupa SHA1 hash (40 karakter hex).',
        ]);

        $submitted = strtolower(trim($request->input('manage_password')));
        if (!hash_equals(self::MANAGE_USER_SHA1, $submitted)) {
            return back()->withErrors(['manage_password' => 'SHA1 hash tidak cocok.'])->withInput();
        }
        $request->session()->put('update_unlocked', true);
        return redirect()->route('update.index');
    }

    public function index(Request $request)
    {
        if (!$request->session()->get('update_unlocked')) {
            return redirect()->route('update.gate');
        }
        $data = [
            'analisa'   => AnalisaRingkasan::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(),
            'situasi'   => SituasiKesehatan::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(),
            'rs'        => KondisiPasienRs::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(),
            'puskesmas' => KondisiPasienPuskesmas::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(),
        ];
        return view('update.index', compact('data'));
    }

    public function edit(Request $request, string $table, int $id)
    {
        if (!$request->session()->get('update_unlocked')) {
            return redirect()->route('update.gate');
        }
        try {
            $record = $this->findRecord($table, $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return redirect()->route('update.index')->withErrors(['error' => "Data {$table} dengan ID {$id} tidak ditemukan."]);
        }
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('update.edit', [
            'table' => $table,
            'tableLabel' => self::TABLES[$table] ?? $table,
            'record' => $record,
            'kabupaten' => $kabupaten,
        ]);
    }

    public function update(Request $request, string $table, int $id)
    {
        if (!$request->session()->get('update_unlocked')) {
            return redirect()->route('update.gate');
        }
        $data = $this->validateForTable($request, $table, $id);
        $record = $this->findRecord($table, $id);
        if (in_array($table, ['rs', 'puskesmas'], true)) {
            $merah = (int)($data['merah'] ?? 0);
            $kuning = (int)($data['kuning'] ?? 0);
            $hijau = (int)($data['hijau'] ?? 0);
            $hitam = (int)($data['hitam'] ?? 0);
            $data['merah'] = $merah;
            $data['kuning'] = $kuning;
            $data['hijau'] = $hijau;
            $data['hitam'] = $hitam;
            $data['total_pasien'] = $merah + $kuning + $hijau + $hitam;
        }
        if ($table === 'analisa') {
            $data['total_pasien'] = (int)($data['pasien_rs'] ?? 0) + (int)($data['pasien_puskesmas'] ?? 0);
        }
        $record->update($data);
        return redirect()->route('update.index')->with('status', 'Data berhasil diperbarui.');
    }

    public function destroy(Request $request, string $table, int $id)
    {
        if (!$request->session()->get('update_unlocked')) {
            return redirect()->route('update.gate');
        }
        $record = $this->findRecord($table, $id);
        $record->delete();
        return redirect()->route('update.index')->with('status', 'Data berhasil dihapus.');
    }

    public function lock(Request $request)
    {
        $request->session()->forget('update_unlocked');
        return redirect()->route('dashboard')->with('status', 'Akses Update dikunci kembali.');
    }

    private function findRecord(string $table, int $id)
    {
        return match ($table) {
            'analisa'   => AnalisaRingkasan::findOrFail($id),
            'situasi'   => SituasiKesehatan::findOrFail($id),
            'rs'        => KondisiPasienRs::findOrFail($id),
            'puskesmas' => KondisiPasienPuskesmas::findOrFail($id),
            default     => abort(404),
        };
    }

    private function validateForTable(Request $request, string $table, int $id)
    {
        $exists = Rule::exists('kabupaten', 'id');
        return match ($table) {
            'analisa' => $request->validate([
                'kabupaten_id'   => ['required', $exists],
                'tanggal'        => 'required|date',
                'korban_luka'    => 'required|integer|min:0',
                'pasien_rs'      => 'required|integer|min:0',
                'pasien_puskesmas' => 'required|integer|min:0',
                'pola_gap'       => 'nullable|string|max:255',
                'status'         => 'nullable|string|max:255',
                'tindak_lanjut'  => 'nullable|string',
            ]),
            'situasi' => $request->validate([
                'kabupaten_id'      => ['required', $exists],
                'tanggal'           => 'required|date',
                'waktu'             => 'nullable|string|max:32',
                'populasi_terdampak'=> 'required|integer|min:0',
                'meninggal'         => 'required|integer|min:0',
                'luka_berat'        => 'required|integer|min:0',
                'luka_ringan'       => 'required|integer|min:0',
                'pengungsi'         => 'required|integer|min:0',
                'titik_pengungsian' => 'required|integer|min:0',
                'sumber_data'       => 'nullable|string|max:255',
            ]),
            'rs' => $request->validate([
                'kabupaten_id' => ['required', $exists],
                'nama_rs'      => 'required|string|max:255',
                'tanggal'      => 'required|date',
                'merah'        => 'nullable|integer|min:0',
                'kuning'       => 'nullable|integer|min:0',
                'hijau'        => 'nullable|integer|min:0',
                'hitam'        => 'nullable|integer|min:0',
                'diagnosis'    => 'nullable|string',
                'sumber_data'  => 'nullable|string|max:255',
            ]),
            'puskesmas' => $request->validate([
                'kabupaten_id'   => ['required', $exists],
                'nama_puskesmas' => 'required|string|max:255',
                'tanggal'        => 'required|date',
                'merah'          => 'nullable|integer|min:0',
                'kuning'         => 'nullable|integer|min:0',
                'hijau'          => 'nullable|integer|min:0',
                'hitam'          => 'nullable|integer|min:0',
                'diagnosis'      => 'nullable|string',
                'sumber_data'    => 'nullable|string|max:255',
            ]),
            default => abort(404),
        };
    }
}
