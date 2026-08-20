<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Data — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', sans-serif; }
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 0.5rem 0.75rem; text-align: left; font-size: 0.875rem; }
    thead { background: #F4F7F5; }
    th { font-weight: 600; color: #4A5A56; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; }
    tbody tr { border-top: 1px solid #E2E8E4; }
    tbody tr:hover { background: #F8FAF9; }
    .num { text-align: right; font-variant-numeric: tabular-nums; }
  </style>
</head>
<body class="min-h-screen px-4 py-6" style="background:#EEF1F0;color:#2D3D3A;">

  <div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
      <div>
        <a href="{{ route('dashboard') }}" class="text-sm text-teal-900 hover:underline">← Kembali ke dashboard</a>
        <h1 class="text-2xl font-semibold text-slate-800 mt-2">Update Data Sitrep NTT</h1>
        <p class="text-sm text-slate-500">Edit atau hapus data tab 1–4. Akses dibuka via SHA1 hash.</p>
      </div>
      <form method="POST" action="{{ route('update.lock') }}">
        @csrf
        <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm px-4 py-2 rounded-lg">Kunci Akses</button>
      </form>
    </div>

    @if(session('status'))
      <div class="bg-emerald-50 text-emerald-700 rounded-lg p-3 mb-4 text-sm">{{ session('status') }}</div>
    @endif

    @foreach(['analisa' => 'Analisa Ringkasan', 'situasi' => 'Situasi Kesehatan', 'rs' => 'Kondisi Pasien RS', 'puskesmas' => 'Kondisi Pasien Puskesmas'] as $key => $label)
      <section class="bg-white rounded-xl shadow-sm p-4 mb-4">
        <h2 class="text-lg font-semibold text-slate-700 mb-3">{{ $label }} <span class="text-xs text-slate-400 font-normal">({{ $data[$key]->count() }} baris)</span></h2>
        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Kabupaten</th>
                @if($key === 'analisa')
                  <th class="num">Korban Luka</th><th class="num">Pasien RS</th><th class="num">Pasien PKM</th><th>Status</th>
                @elseif($key === 'situasi')
                  <th class="num">Meninggal</th><th class="num">Luka Berat</th><th class="num">Luka Ringan</th><th class="num">Pengungsi</th><th class="num">Terdampak</th>
                @else
                  <th>Nama</th><th class="num">Merah</th><th class="num">Kuning</th><th class="num">Hijau</th><th class="num">Hitam</th>
                @endif
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($data[$key] as $row)
                <tr>
                  <td class="text-slate-400">{{ $row->id }}</td>
                  <td>{{ $row->tanggal }}</td>
                  <td>{{ $row->kabupaten->nama_kabupaten ?? '-' }}</td>
                  @if($key === 'analisa')
                    <td class="num">{{ $row->korban_luka }}</td>
                    <td class="num">{{ $row->pasien_rs }}</td>
                    <td class="num">{{ $row->pasien_puskesmas }}</td>
                    <td>{{ $row->status }}</td>
                  @elseif($key === 'situasi')
                    <td class="num">{{ $row->meninggal }}</td>
                    <td class="num">{{ $row->luka_berat }}</td>
                    <td class="num">{{ $row->luka_ringan }}</td>
                    <td class="num">{{ $row->pengungsi }}</td>
                    <td class="num">{{ $row->populasi_terdampak }}</td>
                  @else
                    <td>{{ $key === 'rs' ? $row->nama_rs : $row->nama_puskesmas }}</td>
                    <td class="num">{{ $row->merah }}</td>
                    <td class="num">{{ $row->kuning }}</td>
                    <td class="num">{{ $row->hijau }}</td>
                    <td class="num">{{ $row->hitam }}</td>
                  @endif
                  <td>
                    <a href="{{ route('update.edit', ['table' => $key, 'id' => $row->id]) }}" class="text-teal-900 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('update.destroy', ['table' => $key, 'id' => $row->id]) }}" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:underline text-xs ml-2">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="10" class="text-center text-slate-400 py-3">Tidak ada data.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    @endforeach
  </div>
</body>
</html>
