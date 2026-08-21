<?php

namespace App\Http\Controllers;

use App\Models\AnalisaRingkasan;
use App\Models\Kabupaten;
use App\Models\KondisiPasienPuskesmas;
use App\Models\KondisiPasienRs;
use App\Models\SituasiKesehatan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $analisa = AnalisaRingkasan::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();
        $situasi = SituasiKesehatan::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();
        $rs = KondisiPasienRs::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();
        $puskesmas = KondisiPasienPuskesmas::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();

        // Peta: latest analisa per kabupaten + koordinat dari tabel kabupaten.
        // SQL: ambil row dengan tanggal terbaru untuk tiap kabupaten_id.
        $mapData = DB::table('analisa_ringkasan as a')
            ->join('kabupaten as k', 'k.id', '=', 'a.kabupaten_id')
            ->select(
                'k.id',
                'k.nama_kabupaten',
                'k.latitude',
                'k.longitude',
                'a.tanggal',
                'a.korban_luka',
                'a.pasien_rs',
                'a.pasien_puskesmas',
                'a.total_pasien'
            )
            ->whereIn('a.id', function ($q) {
                $q->select(DB::raw('MAX(id)'))
                    ->from('analisa_ringkasan')
                    ->groupBy('kabupaten_id');
            })
            ->orderBy('k.nama_kabupaten')
            ->get();

        return view('dashboard', compact('analisa', 'situasi', 'rs', 'puskesmas', 'mapData'));
    }
}
