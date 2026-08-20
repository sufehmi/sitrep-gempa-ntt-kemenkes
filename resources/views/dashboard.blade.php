<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Kesehatan - Gempa Bumi di NTT</title>
  <link rel="icon" type="image/png" href="/images/kemenkes-logo.png">
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

  {{-- ====== HEADER (dark teal banner + tabs + logo) ====== --}}
  <header class="bg-teal-900 text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-6 pb-2">
      <div class="flex items-center justify-between gap-4 mb-3">
        <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-white/70">
          <span>♥</span>
          <span>Data Dianalisis dari Laporan Puskris Kemenkes RI</span>
        </div>
        <a href="https://www.kemkes.go.id" target="_blank" rel="noopener" class="shrink-0">
          <img src="/images/kemenkes-logo.png" alt="Kementerian Kesehatan RI" class="h-12 md:h-16 w-auto" />
        </a>
      </div>
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <h1 id="top" class="text-2xl md:text-3xl font-semibold leading-tight">
          Dashboard Kesehatan - Gempa Bumi di NTT
        </h1>
        <div class="text-right text-sm">
          <div class="text-white/60 text-xs uppercase tracking-wider">Data termutakhir</div>
          <div class="font-medium">18 Agustus 2026</div>
        </div>
      </div>
    </div>
    <nav class="max-w-7xl mx-auto px-6 lg:px-8 pb-4">
      <ul class="tab-scroll flex items-center gap-1.5 min-w-min">
        <li><a class="tab-pill active" href="#analisa">Analisa Harian</a></li>
        <li><a class="tab-pill" href="#situasi">Situasi Kesehatan</a></li>
        <li><a class="tab-pill" href="#fasyankes">Identifikasi Kondisi Pasien di Faskes</a></li>
        <li><a class="tab-pill" href="#data-studio">Tim Pendukung Kesehatan</a></li>
        <li><a class="tab-pill" href="#linktree">Informasi lainnya</a></li>
      </ul>
    </nav>
  </header>

  <main class="max-w-7xl mx-auto px-6 lg:px-8 py-8 space-y-6">

    {{-- ============================================================ --}}
    {{-- TAB 1: Analisa Harian (7 kabupaten)                          --}}
    {{-- ============================================================ --}}
    <section id="analisa" class="section-anchor space-y-4">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Analisa Harian Per Kabupaten</h3>
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
                </tr>
              @empty
                <tr><td colspan="8" class="text-center text-slate-400">Belum ada data analisa.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 2: Situasi Kesehatan (7 kabupaten)                       --}}
    {{-- ============================================================ --}}
    <section id="situasi" class="section-anchor space-y-4">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Situasi Kesehatan &amp; Populasi Terdampak</h3>
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
                </tr>
              @empty
                <tr><td colspan="9" class="text-center text-slate-400">Belum ada data situasi.</td></tr>
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
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 3: Identifikasi Kondisi Pasien di Faskes (RS + PKM)     --}}
    {{-- ============================================================ --}}
    <section id="fasyankes" class="section-anchor space-y-4">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Identifikasi Kondisi Pasien di Rumah Sakit</h3>
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
                </tr>
              @empty
                <tr><td colspan="8" class="text-center text-slate-400">Belum ada data RS.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Identifikasi Kondisi Pasien di Puskesmas</h3>
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
                </tr>
              @empty
                <tr><td colspan="9" class="text-center text-slate-400">Belum ada data puskesmas.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 4: Tim Pendukung Kesehatan (Google Data Studio)          --}}
    {{-- ============================================================ --}}
    <section id="data-studio" class="section-anchor">
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-mint-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-slate-800">Tim Pendukung Kesehatan</h3>
          <a href="https://datastudio.google.com/u/0/reporting/35badcdd-7dd0-4208-9bc5-573007f8b8eb/page/Rkk6F" target="_blank" rel="noopener" class="text-sm text-teal-900 hover:underline">Buka di Tab Baru ↗</a>
        </div>
        <div class="p-6">
          <div class="relative w-full overflow-hidden rounded-lg bg-mint-50" style="aspect-ratio: 600 / 443;">
            <iframe
              src="https://datastudio.google.com/embed/reporting/35badcdd-7dd0-4208-9bc5-573007f8b8eb/page/Rkk6F"
              class="absolute inset-0 w-full h-full"
              style="border:0;"
              allowfullscreen
              sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
              title="Tim Pendukung Kesehatan"></iframe>
          </div>
        </div>
      </div>
      <a href="#top" class="btn btn-ghost mt-4">↑ Kembali ke atas</a>
    </section>

    {{-- ============================================================ --}}
    {{-- TAB 5: Informasi lainnya (Linktree + API Docs)              --}}
    {{-- ============================================================ --}}
    <section id="linktree" class="section-anchor">
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-mint-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-slate-800">Informasi lainnya</h3>
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

    <div class="flex justify-center pt-4">
      <a href="{{ route('input.login') }}" class="inline-flex items-center gap-2 bg-teal-900 hover:bg-teal-800 text-white font-semibold text-sm px-6 py-3 rounded-lg transition shadow-sm">
        Input Data
        <span aria-hidden="true">&rarr;</span>
      </a>
    </div>

  </main>

  <footer class="max-w-7xl mx-auto px-6 lg:px-8 py-8 text-center text-xs text-slate-500 space-y-1">
    <p>Dashboard ini dikelola oleh <span class="font-semibold text-slate-700">Pokja RCCE</span> untuk bantu sesama.</p>
    <p>Informasi lengkap RCCE bisa dilihat melalui link berikut: <a href="https://rcce.id" target="_blank" rel="noopener" class="text-teal-900 font-semibold hover:underline">https://rcce.id</a></p>
    <p class="text-slate-400 pt-2">© 2026 Sitrep NTT — Dashboard dibuat dengan Laravel + Tailwind CSS</p>
  </footer>

  <script>
    // Highlight active tab on scroll
    const sections = ['analisa','situasi','fasyankes','data-studio','linktree'];
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
