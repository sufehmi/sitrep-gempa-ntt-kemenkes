<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Sitrep Kesehatan — Gempa Bumi NTT</title>
  <style>
    html, body { margin: 0; padding: 0; min-height: 100vh; background: #EEF1F0; font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif; color: #2D3D3A; }
    * { box-sizing: border-box; }
    a { color: inherit; text-decoration: none; }
    .font-display { font-family: 'Inter', sans-serif; }

    /* Tabs nav pills */
    .tab-pill { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.85); transition: all 0.2s; white-space: nowrap; }
    .tab-pill:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .tab-pill.active { background: #fff; color: #1F4A44; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

    /* Section spacing */
    .section-anchor { scroll-margin-top: 200px; }
    html { scroll-behavior: smooth; }

    /* Status badges */
    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-red { background: #E84D4A; color: #fff; }
    .badge-amber { background: #E8B339; color: #fff; }
    .badge-green { background: #3A8A5C; color: #fff; }
    .badge-gray { background: #9AA5A0; color: #fff; }

    /* Triase color */
    .t-merah { background: #E84D4A; color: #fff; }
    .t-kuning { background: #E8B339; color: #fff; }
    .t-hijau { background: #3A8A5C; color: #fff; }
    .t-hitam { background: #2D3D3A; color: #fff; }

    /* Scrollable tab nav */
    .tab-scroll { overflow-x: auto; scrollbar-width: thin; }
    .tab-scroll::-webkit-scrollbar { height: 4px; }
    .tab-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 2px; }

    /* Tables */
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 0.625rem 0.75rem; text-align: left; font-size: 0.875rem; }
    thead { background: #F4F7F5; }
    th { font-weight: 600; color: #4A5A56; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
    tbody tr { border-top: 1px solid #E2E8E4; }
    tbody tr:hover { background: #F8FAF9; }
    .num { text-align: right; font-variant-numeric: tabular-nums; }

    /* iframe */
    .frame-16x9 { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 0 0 1rem 1rem; }
    .frame-16x9 iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }

    /* Buttons */
    .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; transition: all 0.15s; cursor: pointer; border: 0; }
    .btn-primary { background: #1F4A44; color: #fff; }
    .btn-primary:hover { background: #163632; }
    .btn-ghost { background: transparent; color: #1F4A44; }
    .btn-ghost:hover { background: #E2E8E4; }
  </style>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            teal: {
              950: '#152E2A', 900: '#1F4A44', 800: '#234E48', 700: '#2A5A53',
            },
            mint: {
              50: '#F4F7F5', 100: '#EEF1F0', 200: '#E2E8E4',
            },
            coral: { 500: '#E84D4A', 50: '#FCE4E3' },
            amber: { 500: '#E8B339', 50: '#FCEFCF' },
            sage: { 500: '#3A8A5C', 50: '#E2F0E8' },
            slate: { 800: '#2D3D3A', 600: '#4A5A56', 400: '#7A8A86', 300: '#9AA5A0' },
          }
        }
      }
    }
  </script>
</head>
<body>

  {{-- ====== HEADER (dark teal banner + tabs) ====== --}}
  <header class="bg-teal-900 text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-6 pb-2">
      <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-white/70 mb-3">
        <span>♥</span>
        <span>Data Dianalisis dari Laporan Puskris Kemenkes RI</span>
      </div>
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <h1 id="top" class="text-2xl md:text-3xl font-semibold leading-tight">
          Sitrep Kesehatan — Gempa Bumi NTT
        </h1>
        <div class="text-right text-sm">
          <div class="text-white/60 text-xs uppercase tracking-wider">Data termutakhir</div>
          <div class="font-medium">18 Agustus 2026</div>
          <div class="text-white/70 text-xs">17.00 WIB (populasi); 11.00 WIB (triase RS &amp; Puskesmas)</div>
        </div>
      </div>
    </div>
    <nav class="max-w-7xl mx-auto px-6 lg:px-8 pb-4">
      <ul class="tab-scroll flex items-center gap-1.5 min-w-min">
        <li><a class="tab-pill active" href="#ringkasan">Untuk Pemangku Kebijakan</a></li>
        <li><a class="tab-pill" href="#analisa">Ringkasan Data</a></li>
        <li><a class="tab-pill" href="#fasyankes">Fasyankes</a></li>
        <li><a class="tab-pill" href="#sitrep">SitRep</a></li>
        <li><a class="tab-pill" href="#data-studio">Data Studio</a></li>
        <li><a class="tab-pill" href="#linktree">Linktree</a></li>
        <li><a class="tab-pill" href="#input-data">Input Harian</a></li>
      </ul>
    </nav>
  </header>

  <main class="max-w-7xl mx-auto px-6 lg:px-8 py-8 space-y-6">

    {{-- ============================================================ --}}
    {{-- TAB 1: Ringkasan (hero card + KPI + prioritas)                --}}
    {{-- ============================================================ --}}
    <section id="ringkasan" class="section-anchor space-y-6">

      {{-- Hero / status card --}}
      <div class="bg-teal-900 text-white rounded-2xl p-6 lg:p-8 shadow-sm">
        <h2 class="text-lg md:text-xl font-semibold leading-snug mb-3">
          1 rumah sakit di 1 kabupaten masih belum beroperasi penuh.
          <span class="text-coral-500 font-bold">7 isu prioritas tinggi</span> menunggu keputusan.
        </h2>
        <div class="flex flex-wrap gap-2 mt-4">
          <span class="bg-white/10 px-3 py-1.5 rounded-full text-xs">RS belum operasional: <strong>1 → 1</strong> <span class="text-white/60">(stabil)</span></span>
          <span class="bg-white/10 px-3 py-1.5 rounded-full text-xs">Item logistik masih gap: <strong>158 → 158</strong> <span class="text-white/60">(stabil)</span></span>
          <span class="bg-white/10 px-3 py-1.5 rounded-full text-xs">Pengungsi (KK): <strong>6.680 → 43.113</strong> <span class="text-sage-500 font-semibold">(membaik)</span></span>
          <a href="#analisa" class="ml-auto text-sm text-white/80 hover:text-white underline">Lihat rincian ›</a>
        </div>
      </div>

      {{-- KPI 3-column --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-coral-50 border-l-4 border-coral-500 rounded-2xl p-6">
          <div class="text-xs uppercase tracking-wider text-coral-500 font-bold">RS Belum Operasional</div>
          <div class="text-4xl font-bold mt-2 text-slate-800">{{ $kpi['rs_belum_operasional'] }}</div>
          <div class="text-xs text-slate-400 mt-1">dari total 6 RS</div>
        </div>
        <div class="bg-coral-50 border-l-4 border-coral-500 rounded-2xl p-6">
          <div class="text-xs uppercase tracking-wider text-coral-500 font-bold">Korban (Meninggal + Luka Berat)</div>
          <div class="text-4xl font-bold mt-2 text-slate-800">{{ number_format($kpi['meninggal'] + $kpi['luka_berat']) }}</div>
          <div class="text-xs text-slate-400 mt-1">Meninggal {{ $kpi['meninggal'] }} + Luka Berat {{ $kpi['luka_berat'] }}</div>
        </div>
        <div class="bg-amber-50 border-l-4 border-amber-500 rounded-2xl p-6">
          <div class="text-xs uppercase tracking-wider text-amber-500 font-bold">Isu Menunggu Keputusan</div>
          <div class="text-4xl font-bold mt-2 text-slate-800">{{ $kpi['isu_prioritas'] }}</div>
          <div class="text-xs text-slate-400 mt-1">{{ $kpi['item_logistik_gap'] }} item logistik masih gap</div>
        </div>
      </div>

      {{-- Prioritas list --}}
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-mint-200 flex items-center gap-2">
          <span class="text-teal-900 text-lg">🛡</span>
          <h3 class="text-base font-semibold text-slate-800">Keputusan yang Perlu Diambil</h3>
        </div>
        <div class="divide-y divide-mint-200">
          @foreach($analisa->take(7) as $row)
            <div class="px-6 py-4 flex items-start gap-4 {{ $row->status && str_contains(strtolower($row->status), 'tinggi') ? 'bg-coral-50/50' : ($row->status && str_contains(strtolower($row->status), 'sedang') ? 'bg-amber-50/50' : '') }}">
              <span class="badge {{ $row->status && str_contains(strtolower($row->status), 'tinggi') ? 'badge-red' : 'badge-amber' }} mt-1 shrink-0">
                {{ $row->status && str_contains(strtolower($row->status), 'tinggi') ? 'Tinggi' : 'Sedang' }}
              </span>
              <div class="flex-1 min-w-0">
                <div class="font-semibold text-slate-800">{{ $row->kabupaten->nama_kabupaten }} — {{ $row->pola_gap ?: 'Verifikasi data diperlukan' }}</div>
                <div class="text-sm text-slate-400 mt-0.5">{{ $row->tindak_lanjut ?: 'Belum ada tindak lanjut' }}</div>
                <span class="text-xs text-slate-300 mt-1 inline-block">Fasyankes</span>
              </div>
              <span class="text-slate-300 text-lg">›</span>
            </div>
          @endforeach
        </div>
        <div class="px-6 py-3 text-center border-t border-mint-200 text-sm text-slate-400">
          +5 isu lain, prioritas sedang/rendah
        </div>
      </div>

      {{-- Yang sudah dikerjakan --}}
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-mint-200 flex items-center gap-2">
          <span class="text-sage-500 text-lg">✓</span>
          <h3 class="text-base font-semibold text-slate-800">Yang Sudah Dikerjakan</h3>
        </div>
        <div class="divide-y divide-mint-200">
          <div class="px-6 py-3 flex items-center gap-3"><span class="text-sage-500">●</span> Pendataan 7 kabupaten terdampak selesai</div>
          <div class="px-6 py-3 flex items-center gap-3"><span class="text-sage-500">●</span> 6 RS triase dilakukan</div>
          <div class="px-6 py-3 flex items-center gap-3"><span class="text-sage-500">●</span> 12 puskesmas triase dilakukan</div>
          <div class="px-6 py-3 flex items-center gap-3"><span class="text-sage-500">●</span> Data import dari Excel Puskris Kemenkes RI</div>
        </div>
      </div>

      <a href="#analisa" class="btn btn-ghost">Lihat seluruh data operasional ›</a>
      <a href="#top" class="btn btn-ghost float-right">↑ Kembali ke atas</a>
      <div class="clear-both"></div>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 2: Analisa Ringkasan (7 kabupaten)                       --}}
    {{-- ============================================================ --}}
    <section id="analisa" class="section-anchor space-y-4">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">📋 Ringkasan Data — Analisa Per Kabupaten</h3>
        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kabupaten</th>
                <th class="num">Korban Luka</th>
                <th class="num">Pasien RS</th>
                <th class="num">Pasien PKM</th>
                <th class="num">Total Fasyankes</th>
                <th>Pola Gap</th>
                <th>Status</th>
                <th>Tindak Lanjut</th>
              </tr>
            </thead>
            <tbody>
              @forelse($analisa as $row)
                <tr>
                  <td>{{ $row->tanggal->format('Y-m-d') }}</td>
                  <td><strong>{{ $row->kabupaten->nama_kabupaten }}</strong></td>
                  <td class="num">{{ number_format($row->korban_luka) }}</td>
                  <td class="num">{{ number_format($row->pasien_rs) }}</td>
                  <td class="num">{{ number_format($row->pasien_puskesmas) }}</td>
                  <td class="num font-semibold">{{ number_format($row->total_pasien) }}</td>
                  <td><span class="text-xs text-slate-400">{{ Str::limit($row->pola_gap, 50) }}</span></td>
                  <td>{{ $row->status }}</td>
                  <td><span class="text-xs text-slate-400">{{ Str::limit($row->tindak_lanjut, 60) }}</span></td>
                </tr>
              @empty
                <tr><td colspan="9" class="text-center text-slate-400">Belum ada data analisa.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 3: Fasyankes (RS + Puskesmas)                            --}}
    {{-- ============================================================ --}}
    <section id="fasyankes" class="section-anchor space-y-4">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">🏥 Rumah Sakit</h3>
        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kabupaten</th>
                <th>Nama RS</th>
                <th class="num t-merah">Merah</th>
                <th class="num t-kuning">Kuning</th>
                <th class="num t-hijau">Hijau</th>
                <th class="num t-hitam">Hitam</th>
                <th class="num">Total</th>
                <th>Sumber</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rs as $row)
                <tr>
                  <td>{{ $row->tanggal->format('Y-m-d') }}</td>
                  <td>{{ $row->kabupaten->nama_kabupaten }}</td>
                  <td><strong>{{ $row->nama_rs }}</strong></td>
                  <td class="num t-merah font-bold">{{ $row->merah }}</td>
                  <td class="num t-kuning">{{ $row->kuning }}</td>
                  <td class="num t-hijau">{{ $row->hijau }}</td>
                  <td class="num t-hitam">{{ $row->hitam }}</td>
                  <td class="num font-semibold">{{ $row->total_pasien }}</td>
                  <td><span class="text-xs text-slate-400">{{ $row->sumber_data }}</span></td>
                </tr>
              @empty
                <tr><td colspan="9" class="text-center text-slate-400">Belum ada data RS.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">⚕️ Puskesmas</h3>
        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kabupaten</th>
                <th>Nama Puskesmas</th>
                <th class="num t-merah">Merah</th>
                <th class="num t-kuning">Kuning</th>
                <th class="num t-hijau">Hijau</th>
                <th class="num t-hitam">Hitam</th>
                <th class="num">Total</th>
                <th>Diagnosis/Catatan</th>
                <th>Sumber</th>
              </tr>
            </thead>
            <tbody>
              @forelse($puskesmas as $row)
                <tr>
                  <td>{{ $row->tanggal->format('Y-m-d') }}</td>
                  <td>{{ $row->kabupaten->nama_kabupaten }}</td>
                  <td><strong>{{ $row->nama_puskesmas }}</strong></td>
                  <td class="num t-merah font-bold">{{ $row->merah }}</td>
                  <td class="num t-kuning">{{ $row->kuning }}</td>
                  <td class="num t-hijau">{{ $row->hijau }}</td>
                  <td class="num t-hitam">{{ $row->hitam }}</td>
                  <td class="num font-semibold">{{ $row->total_pasien }}</td>
                  <td><span class="text-xs text-slate-400">{{ Str::limit($row->diagnosis, 50) }}</span></td>
                  <td><span class="text-xs text-slate-400">{{ $row->sumber_data }}</span></td>
                </tr>
              @empty
                <tr><td colspan="10" class="text-center text-slate-400">Belum ada data puskesmas.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 4: SitRep (tabel situasi 7 kab)                          --}}
    {{-- ============================================================ --}}
    <section id="sitrep" class="section-anchor space-y-4">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">📈 SitRep — Situasi Kesehatan &amp; Populasi Terdampak</h3>
        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Kabupaten</th>
                <th class="num">Populasi Terdampak</th>
                <th class="num">Meninggal</th>
                <th class="num">Luka Berat</th>
                <th class="num">Luka Ringan</th>
                <th class="num">Pengungsi</th>
                <th class="num">Titik Pengungsian</th>
                <th>Sumber</th>
              </tr>
            </thead>
            <tbody>
              @forelse($situasi as $row)
                <tr>
                  <td>{{ $row->tanggal->format('Y-m-d') }}</td>
                  <td>{{ $row->waktu }}</td>
                  <td><strong>{{ $row->kabupaten->nama_kabupaten }}</strong></td>
                  <td class="num">{{ number_format($row->populasi_terdampak) }}</td>
                  <td class="num text-coral-500 font-bold">{{ number_format($row->meninggal) }}</td>
                  <td class="num">{{ number_format($row->luka_berat) }}</td>
                  <td class="num">{{ number_format($row->luka_ringan) }}</td>
                  <td class="num">{{ number_format($row->pengungsi) }}</td>
                  <td class="num">{{ number_format($row->titik_pengungsian) }}</td>
                  <td><span class="text-xs text-slate-400">{{ $row->sumber_data }}</span></td>
                </tr>
              @empty
                <tr><td colspan="10" class="text-center text-slate-400">Belum ada data situasi.</td></tr>
              @endforelse
            </tbody>
            @if($situasi->count() > 0)
              <tfoot>
                <tr class="bg-mint-50 font-semibold">
                  <td colspan="3">TOTAL</td>
                  <td class="num">{{ number_format($situasi->sum('populasi_terdampak')) }}</td>
                  <td class="num">{{ number_format($situasi->sum('meninggal')) }}</td>
                  <td class="num">{{ number_format($situasi->sum('luka_berat')) }}</td>
                  <td class="num">{{ number_format($situasi->sum('luka_ringan')) }}</td>
                  <td class="num">{{ number_format($situasi->sum('pengungsi')) }}</td>
                  <td class="num">{{ number_format($situasi->sum('titik_pengungsian')) }}</td>
                  <td></td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
      </div>

      {{-- Logistik & SDM placeholder (sesuai referensi) --}}
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">📦 Logistik &amp; SDM</h3>
        <p class="text-slate-400 text-sm">Data logistik dan SDM akan ditambahkan sesuai kebutuhan operasional.</p>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div class="bg-amber-50 rounded-xl p-4 border-l-4 border-amber-500">
            <div class="text-xs uppercase tracking-wider text-amber-500 font-bold">Item Logistik Gap</div>
            <div class="text-2xl font-bold mt-1 text-slate-800">{{ $kpi['item_logistik_gap'] }}</div>
            <div class="text-xs text-slate-400 mt-1">Item yang masih kurang di lapangan</div>
          </div>
          <div class="bg-sage-50 rounded-xl p-4 border-l-4 border-sage-500">
            <div class="text-xs uppercase tracking-wider text-sage-500 font-bold">Total Fasyankes</div>
            <div class="text-2xl font-bold mt-1 text-slate-800">{{ $kpi['total_pasien'] }}</div>
            <div class="text-xs text-slate-400 mt-1">RS + Puskesmas yang beroperasi</div>
          </div>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 5: Google Data Studio                                     --}}
    {{-- ============================================================ --}}
    <section id="data-studio" class="section-anchor">
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-mint-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-slate-800">📊 Google Data Studio — Visualisasi Lengkap</h3>
          <a href="https://datastudio.google.com/u/0/reporting/35badcdd-7dd0-4208-9bc5-573007f8b8eb/page/Rkk6F" target="_blank" class="text-sm text-teal-900 hover:underline">Buka di Tab Baru ↗</a>
        </div>
        <div class="frame-16x9" style="min-height:75vh;">
          <iframe src="https://datastudio.google.com/embed/reporting/35badcdd-7dd0-4208-9bc5-573007f8b8eb/page/Rkk6F" allowfullscreen></iframe>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost mt-4">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 6: Linktree                                              --}}
    {{-- ============================================================ --}}
    <section id="linktree" class="section-anchor">
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-mint-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-slate-800">🔗 Linktree — Sitrep NTT</h3>
        </div>
        <div class="px-6 py-8 text-center">
          <p class="text-slate-600 mb-4">Kumpulan tautan resmi Sitrep Gempa NTT.</p>
          <a href="https://linktr.ee/gempantt" target="_blank" rel="noopener" class="inline-block bg-teal-900 hover:bg-teal-800 text-white font-semibold px-6 py-3 rounded-lg transition">
            Buka Linktree Gempa NTT
            <span class="ml-2">↗</span>
          </a>
          <p class="text-xs text-slate-400 mt-3 break-all">https://linktr.ee/gempantt</p>

          <div class="mt-6 pt-6 border-t border-slate-200">
            <p class="text-slate-600 mb-3 text-sm">Akses data dashboard via API publik (read-only).</p>
            <a href="/api-docs" class="inline-block bg-slate-800 hover:bg-slate-700 text-white font-medium px-5 py-2.5 rounded-lg transition text-sm">
              Lihat API Documentation
              <span class="ml-1">→</span>
            </a>
          </div>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost mt-4">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 7: Input Data                                            --}}
    {{-- ============================================================ --}}
    <section id="input-data" class="section-anchor">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Input Data</h3>
        @auth
          <p class="text-sm text-slate-600 mb-4">
            Login sebagai: <strong>{{ auth()->user()->username }}</strong>
            ({{ auth()->user()->name ?? '-' }})
            <form method="POST" action="{{ route('input.logout') }}" class="inline ml-2">
              @csrf
              <button type="submit" class="text-coral-500 hover:underline text-sm">Logout</button>
            </form>
          </p>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <a href="{{ route('input.analisa') }}" class="btn btn-primary">Analisa Ringkasan</a>
            <a href="{{ route('input.situasi') }}" class="btn btn-primary">Situasi Kesehatan</a>
            <a href="{{ route('input.rs') }}" class="btn btn-primary">Data RS</a>
            <a href="{{ route('input.puskesmas') }}" class="btn btn-primary">Data Puskesmas</a>
          </div>
          <div class="mt-6 pt-4 border-t border-mint-200">
            <a href="{{ route('users.gate') }}" class="btn btn-ghost border border-teal-900">Manage User</a>
          </div>
        @else
          <p class="text-sm text-slate-600 mb-4">Anda belum login. Silakan login untuk mengakses fitur input data.</p>
          <a href="{{ route('input.login') }}" class="btn btn-primary">Login</a>
        @endauth
      </div>
      <a href="#top" class="btn btn-ghost mt-4">↑ Kembali ke atas</a>
    </section>

  </main>

  <footer class="max-w-7xl mx-auto px-6 lg:px-8 py-8 text-center text-xs text-slate-500 space-y-1">
    <p>Dashboard ini dikelola oleh <span class="font-semibold text-slate-700">Pokja RCCE</span> untuk bantu sesama.</p>
    <p>Informasi lengkap RCCE bisa dilihat melalui link berikut: <a href="https://rcce.id" target="_blank" rel="noopener" class="text-teal-900 font-semibold hover:underline">https://rcce.id</a></p>
    <p class="text-slate-400 pt-2">© 2026 Sitrep NTT — Dashboard dibuat dengan Laravel + Tailwind CSS</p>
  </footer>

  <script>
    // Highlight active tab on scroll
    const sections = ['ringkasan','analisa','fasyankes','sitrep','data-studio','linktree','input-data'];
    document.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 220) current = id;
      });
      document.querySelectorAll('.tab-pill').forEach(p => {
        p.classList.toggle('active', p.getAttribute('href') === '#' + current);
      });
    });
  </script>
</body>
</html>
