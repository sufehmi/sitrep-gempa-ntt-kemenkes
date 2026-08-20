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

        return view('dashboard', compact('analisa', 'situasi', 'rs', 'puskesmas'));
    }
}
