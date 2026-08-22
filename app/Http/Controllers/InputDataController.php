<?php

namespace App\Http\Controllers;

use App\Models\AnalisaRingkasan;
use App\Models\Kabupaten;
use App\Models\KondisiPasienPuskesmas;
use App\Models\KondisiPasienRs;
use App\Models\SituasiKesehatan;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InputDataController extends Controller
{
    public function showLogin(): View
    {
        return view('input.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('input.index');
        }
        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('input.login');
    }

    public function index(): View
    {
        return view('input.index', ['user' => Auth::user()]);
    }

    public function createAnalisa(): View
    {
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('input.analisa', ['kabupaten' => $kabupaten, 'record' => null]);
    }

    public function storeAnalisa(Request $request)
    {
        $data = $request->validate([
            'kabupaten_id' => ['required', Rule::exists('kabupaten', 'id')],
            'tanggal' => 'required|date',
            'korban_luka' => 'required|integer|min:0',
            'pasien_rs' => 'required|integer|min:0',
            'pasien_puskesmas' => 'required|integer|min:0',
            'pola_gap' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'tindak_lanjut' => 'nullable|string',
        ]);
        $data['total_pasien'] = (int)$data['pasien_rs'] + (int)$data['pasien_puskesmas'];
        $this->upsertByKabTanggal(AnalisaRingkasan::class, $data, 'kabupaten_id', 'tanggal');
        return redirect()->route('input.index')->with('status', 'Data analisa berhasil disimpan.');
    }

    public function createSituasi(): View
    {
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('input.situasi', ['kabupaten' => $kabupaten, 'record' => null]);
    }

    public function storeSituasi(Request $request)
    {
        $data = $request->validate([
            'kabupaten_id' => ['required', Rule::exists('kabupaten', 'id')],
            'tanggal' => 'required|date',
            'waktu' => 'nullable|string|max:32',
            'populasi_terdampak' => 'required|integer|min:0',
            'meninggal' => 'required|integer|min:0',
            'luka_berat' => 'required|integer|min:0',
            'luka_ringan' => 'required|integer|min:0',
            'pengungsi' => 'required|integer|min:0',
            'titik_pengungsian' => 'required|integer|min:0',
            'sumber_data' => 'nullable|string|max:255',
        ]);
        $data['waktu'] = $data['waktu'] ?? '00:01';
        $this->upsertByKabTanggal(SituasiKesehatan::class, $data, 'kabupaten_id', 'tanggal');
        return redirect()->route('input.index')->with('status', 'Data situasi berhasil disimpan.');
    }

    public function createRs(): View
    {
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('input.rs', ['kabupaten' => $kabupaten, 'record' => null]);
    }

    public function storeRs(Request $request)
    {
        $data = $request->validate([
            'kabupaten_id' => ['required', Rule::exists('kabupaten', 'id')],
            'nama_rs' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'merah' => 'nullable|integer|min:0',
            'kuning' => 'nullable|integer|min:0',
            'hijau' => 'nullable|integer|min:0',
            'hitam' => 'nullable|integer|min:0',
            'diagnosis' => 'nullable|string',
            'sumber_data' => 'nullable|string|max:255',
        ]);
        // Fasyankes yang tidak kedatangan pasien baru boleh disimpan apa adanya
        // (semua zona = 0). fix per 2026-08-22: input 0 sebelumnya ditolak oleh
        // guard `if (... === 0)` yang sekarang dihapus.
        $merah = (int)($data['merah'] ?? 0);
        $kuning = (int)($data['kuning'] ?? 0);
        $hijau = (int)($data['hijau'] ?? 0);
        $hitam = (int)($data['hitam'] ?? 0);
        $data['merah'] = $merah;
        $data['kuning'] = $kuning;
        $data['hijau'] = $hijau;
        $data['hitam'] = $hitam;
        $data['total_pasien'] = $merah + $kuning + $hijau + $hitam;
        // fix 2026-08-22: composite key HARUS ikut kabupaten_id supaya nama RS
        // yang sama di kabupaten berbeda pada tanggal yang sama tidak saling
        // menimpa (lihat juga migration 2026_08_22_090000).
        try {
            KondisiPasienRs::updateOrCreate(
                ['nama_rs' => $data['nama_rs'], 'kabupaten_id' => $data['kabupaten_id'], 'tanggal' => $data['tanggal']],
                $data
            );
        } catch (UniqueConstraintViolationException) {
            // safety net utk race (dua request concurrent dgn key sama persis).
            // lookup ulang dgn composite key yg lengkap lalu update.
            KondisiPasienRs::where('nama_rs', $data['nama_rs'])
                ->where('kabupaten_id', $data['kabupaten_id'])
                ->whereDate('tanggal', $data['tanggal'])
                ->update($data);
        }
        return redirect()->route('input.index')->with('status', 'Data RS berhasil disimpan.');
    }

    public function createPuskesmas(): View
    {
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('input.puskesmas', ['kabupaten' => $kabupaten, 'record' => null]);
    }

    public function storePuskesmas(Request $request)
    {
        $data = $request->validate([
            'kabupaten_id' => ['required', Rule::exists('kabupaten', 'id')],
            'nama_puskesmas' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'merah' => 'nullable|integer|min:0',
            'kuning' => 'nullable|integer|min:0',
            'hijau' => 'nullable|integer|min:0',
            'hitam' => 'nullable|integer|min:0',
            'diagnosis' => 'nullable|string',
            'sumber_data' => 'nullable|string|max:255',
        ]);
        // Fasyankes yang tidak kedatangan pasien baru boleh disimpan apa adanya
        // (semua zona = 0). fix per 2026-08-22: input 0 sebelumnya ditolak oleh
        // guard `if (... === 0)` yang sekarang dihapus.
        $merah = (int)($data['merah'] ?? 0);
        $kuning = (int)($data['kuning'] ?? 0);
        $hijau = (int)($data['hijau'] ?? 0);
        $hitam = (int)($data['hitam'] ?? 0);
        $data['merah'] = $merah;
        $data['kuning'] = $kuning;
        $data['hijau'] = $hijau;
        $data['hitam'] = $hitam;
        $data['total_pasien'] = $merah + $kuning + $hijau + $hitam;
        // fix 2026-08-22: composite key HARUS ikut kabupaten_id supaya nama
        // puskesmas yang sama di kabupaten berbeda pada tanggal yang sama
        // tidak saling menimpa (lihat juga migration 2026_08_22_090000).
        try {
            KondisiPasienPuskesmas::updateOrCreate(
                ['nama_puskesmas' => $data['nama_puskesmas'], 'kabupaten_id' => $data['kabupaten_id'], 'tanggal' => $data['tanggal']],
                $data
            );
        } catch (UniqueConstraintViolationException) {
            // safety net utk race (dua request concurrent dgn key sama persis).
            KondisiPasienPuskesmas::where('nama_puskesmas', $data['nama_puskesmas'])
                ->where('kabupaten_id', $data['kabupaten_id'])
                ->whereDate('tanggal', $data['tanggal'])
                ->update($data);
        }
        return redirect()->route('input.index')->with('status', 'Data puskesmas berhasil disimpan.');
    }

    /**
     * Upsert by composite key (kabupaten_id, tanggal) handling unique-constraint races.
     * updateOrCreate() in Laravel 13 routes through firstOrCreate() which throws on race;
     * we catch the SQLSTATE 23000 and retry as a plain update on the existing row.
     */
    private function upsertByKabTanggal(string $modelClass, array $data, string $key1, string $key2): void
    {
        // Normalize date to Y-m-d so it matches the SQLite 'YYYY-MM-DD HH:MM:SS' string
        $value1 = $data[$key1];
        $value2 = is_string($data[$key2]) ? \Carbon\Carbon::parse($data[$key2])->format('Y-m-d') : $data[$key2];
        $match = [$key1 => $value1, $key2 => $value2];
        try {
            $modelClass::create($data);
        } catch (UniqueConstraintViolationException) {
            // Fall back to UPDATE when row already exists. Use whereDate so we match the stored
            // datetime value against the Y-m-d string the form sent.
            $modelClass::where($key1, $value1)->whereDate($key2, $value2)->update($data);
        }
    }
}
