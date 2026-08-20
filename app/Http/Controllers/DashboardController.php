<?php

namespace App\Http\Controllers;

use App\Models\AnalisaRingkasan;
use App\Models\KondisiPasienPuskesmas;
use App\Models\KondisiPasienRs;
use App\Models\SituasiKesehatan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $analisa = AnalisaRingkasan::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();
        $situasi = SituasiKesehatan::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();
        $rs = KondisiPasienRs::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();
        $puskesmas = KondisiPasienPuskesmas::with('kabupaten')->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get();

        $kpi = [
            'korban_luka' => $analisa->sum('korban_luka'),
            'meninggal' => $situasi->sum('meninggal'),
            'luka_berat' => $situasi->sum('luka_berat'),
            'luka_ringan' => $situasi->sum('luka_ringan'),
            'pengungsi' => $situasi->sum('pengungsi'),
            'populasi_terdampak' => $situasi->sum('populasi_terdampak'),
            'total_pasien' => $rs->sum('total_pasien') + $puskesmas->sum('total_pasien'),
            'rs_belum_operasional' => 1, // placeholder dari referensi
            'isu_prioritas' => 7,
            'item_logistik_gap' => 158,
        ];

        return view('dashboard', compact('analisa', 'situasi', 'rs', 'puskesmas', 'kpi'));
    }
}
