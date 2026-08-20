<?php

namespace App\Http\Controllers;

use App\Models\AnalisaRingkasan;
use App\Models\Kabupaten;
use App\Models\KondisiPasienPuskesmas;
use App\Models\KondisiPasienRs;
use App\Models\SituasiKesehatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function meta(): JsonResponse
    {
        return response()->json([
            'service' => 'Sitrep NTT API',
            'version' => 'v1',
            'generated_at' => now()->toIso8601String(),
            'endpoints' => [
                'GET /api/v1/kabupaten' => 'Daftar 7 kabupaten (Sikka, Manggarai Timur, Manggarai, Ngada, Nagekeo, Ende, Manggarai Barat).',
                'GET /api/v1/analisa' => 'Analisa ringkasan harian. Query: ?kabupaten_id=&tanggal=',
                'GET /api/v1/situasi' => 'Situasi kesehatan & populasi. Query: ?kabupaten_id=&tanggal=',
                'GET /api/v1/rs' => 'Kondisi pasien di RS. Query: ?kabupaten_id=&tanggal=',
                'GET /api/v1/puskesmas' => 'Kondisi pasien di Puskesmas. Query: ?kabupaten_id=&tanggal=',
            ],
            'auth' => 'Header X-API-Key (atau query ?api_key=). Buat & hapus di /users → Manage User.',
        ]);
    }

    public function kabupaten(): JsonResponse
    {
        $data = Kabupaten::orderBy('id')->get(['id', 'nama_kabupaten', 'kode_kabupaten']);
        return $this->ok($data, 'kabupaten');
    }

    public function analisa(Request $request): JsonResponse
    {
        $q = AnalisaRingkasan::with('kabupaten:id,nama_kabupaten');
        $this->applyFilters($q, $request);
        return $this->ok($q->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(), 'analisa');
    }

    public function situasi(Request $request): JsonResponse
    {
        $q = SituasiKesehatan::with('kabupaten:id,nama_kabupaten');
        $this->applyFilters($q, $request);
        return $this->ok($q->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(), 'situasi');
    }

    public function rs(Request $request): JsonResponse
    {
        $q = KondisiPasienRs::with('kabupaten:id,nama_kabupaten');
        if ($request->filled('kabupaten_id')) $q->where('kabupaten_id', $request->integer('kabupaten_id'));
        if ($request->filled('tanggal')) $q->whereDate('tanggal', $request->date('tanggal')->format('Y-m-d'));
        return $this->ok($q->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(), 'rs');
    }

    public function puskesmas(Request $request): JsonResponse
    {
        $q = KondisiPasienPuskesmas::with('kabupaten:id,nama_kabupaten');
        if ($request->filled('kabupaten_id')) $q->where('kabupaten_id', $request->integer('kabupaten_id'));
        if ($request->filled('tanggal')) $q->whereDate('tanggal', $request->date('tanggal')->format('Y-m-d'));
        return $this->ok($q->orderBy('tanggal', 'desc')->orderBy('kabupaten_id')->get(), 'puskesmas');
    }

    private function applyFilters($q, Request $request): void
    {
        if ($request->filled('kabupaten_id')) $q->where('kabupaten_id', $request->integer('kabupaten_id'));
        if ($request->filled('tanggal')) $q->whereDate('tanggal', $request->date('tanggal')->format('Y-m-d'));
    }

    private function ok(iterable $data, string $key): JsonResponse
    {
        $count = is_countable($data) ? count($data) : (is_array($data) ? count($data) : 0);
        return response()->json([
            $key => $data,
            'meta' => [
                'count' => $count,
                'generated_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
