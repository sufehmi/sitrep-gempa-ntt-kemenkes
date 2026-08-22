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
    tbody tr.row-selected { background: #FEF3F2; }
    .num { text-align: right; font-variant-numeric: tabular-nums; }
    .col-check { width: 2.5rem; }
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
    @if($errors->any())
      <div class="bg-red-50 text-red-700 rounded-lg p-3 mb-4 text-sm">
        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
      </div>
    @endif

    @foreach(['analisa' => 'Analisa Ringkasan', 'situasi' => 'Situasi Kesehatan', 'rs' => 'Kondisi Pasien RS', 'puskesmas' => 'Kondisi Pasien Puskesmas'] as $key => $label)
      @php $rows = $data[$key]; @endphp
      <section class="bg-white rounded-xl shadow-sm p-4 mb-4" data-section="{{ $key }}">
        <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
          <h2 class="text-lg font-semibold text-slate-700">
            {{ $label }}
            <span class="text-xs text-slate-400 font-normal">({{ $rows->count() }} baris)</span>
          </h2>
          <form method="POST" action="{{ route('update.bulk-destroy') }}"
                class="bulk-form flex items-center gap-2"
                data-table="{{ $key }}"
                data-label="{{ $label }}"
                onsubmit="return window.__bulkSubmit(this);">
            @csrf
            <input type="hidden" name="table" value="{{ $key }}">
            {{-- hidden container for selected IDs --}}
            <span class="ids-container"></span>
            <button type="submit"
                    class="bulk-btn bg-red-100 hover:bg-red-200 text-red-700 text-sm px-3 py-2 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed"
                    disabled>
              Hapus yang dipilih (<span class="bulk-count">0</span>)
            </button>
            <button type="button"
                    class="bulk-clear bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm px-3 py-2 rounded-lg hidden"
                    onclick="window.__bulkClear('{{ $key }}')">
              Bersihkan pilihan
            </button>
          </form>
        </div>

        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th class="col-check">
                  <input type="checkbox"
                         class="bulk-master cursor-pointer"
                         data-table="{{ $key }}"
                         onclick="window.__bulkToggleAll('{{ $key }}', this)">
                </th>
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
              @forelse($rows as $row)
                <tr data-table="{{ $key }}" data-id="{{ $row->id }}">
                    <td>
                      <input type="checkbox"
                             class="bulk-row cursor-pointer"
                             data-table="{{ $key }}"
                             data-id="{{ $row->id }}"
                             data-label="{{ $row->tanggal }} • {{ $row->kabupaten->nama_kabupaten ?? '-' }}"
                             onclick="window.__bulkToggle(this)">
                    </td>
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

  <script>
    /**
     * Bulk-select state — per table.
     * Map<key, Array<{id, label}>>
     */
    window.__bulkState = {};

    function __bulkToggle(cb) {
      const key = cb.dataset.table;
      const id  = parseInt(cb.dataset.id, 10);
      const label = cb.dataset.label;
      if (!window.__bulkState[key]) window.__bulkState[key] = [];
      const arr = window.__bulkState[key];
      const idx = arr.findIndex(x => x.id === id);
      if (cb.checked && idx === -1) arr.push({ id, label });
      else if (!cb.checked && idx !== -1) arr.splice(idx, 1);
      __bulkRefresh(key);
    }

    function __bulkToggleAll(key, master) {
      const arr = window.__bulkState[key] = window.__bulkState[key] || [];
      const rows = document.querySelectorAll(`input.bulk-row[data-table="${key}"]`);
      rows.forEach(cb => {
        cb.checked = master.checked;
        const id  = parseInt(cb.dataset.id, 10);
        const idx = arr.findIndex(x => x.id === id);
        if (master.checked && idx === -1) arr.push({ id, label: cb.dataset.label });
        else if (!master.checked && idx !== -1) arr.splice(idx, 1);
      });
      __bulkRefresh(key);
    }

    function __bulkClear(key) {
      window.__bulkState[key] = [];
      document.querySelectorAll(`input.bulk-row[data-table="${key}"]`).forEach(cb => cb.checked = false);
      const master = document.querySelector(`input.bulk-master[data-table="${key}"]`);
      if (master) master.checked = false;
      __bulkRefresh(key);
    }

    function __bulkRefresh(key) {
      const arr  = window.__bulkState[key] || [];
      const form = document.querySelector(`form.bulk-form[data-table="${key}"]`);
      if (!form) return;
      const btn   = form.querySelector('.bulk-btn');
      const count = form.querySelector('.bulk-count');
      const clear = form.querySelector('.bulk-clear');
      const cont  = form.querySelector('.ids-container');

      // populate hidden inputs
      cont.innerHTML = '';
      arr.forEach(({id}) => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'ids[]';
        inp.value = String(id);
        cont.appendChild(inp);
      });

      // update count + button state
      count.textContent = String(arr.length);
      btn.disabled = arr.length === 0;
      clear.classList.toggle('hidden', arr.length === 0);

      // highlight selected rows
      document.querySelectorAll(`tr[data-table="${key}"]`).forEach(tr => {
        const id = parseInt(tr.dataset.id, 10);
        tr.classList.toggle('row-selected', arr.some(x => x.id === id));
      });

      // master checkbox indeterminate / checked state
      const master = document.querySelector(`input.bulk-master[data-table="${key}"]`);
      if (master) {
        const total = document.querySelectorAll(`input.bulk-row[data-table="${key}"]`).length;
        master.checked  = arr.length === total && total > 0;
        master.indeterminate = arr.length > 0 && arr.length < total;
      }
    }

    /**
     * Confirm dialog dengan preview daftar baris yang akan dihapus.
     * Returns true kalau user konfirmasi, false kalau cancel.
     */
    window.__bulkSubmit = function(form) {
      const key = form.dataset.table;
      const label = form.dataset.label;
      const arr = window.__bulkState[key] || [];
      if (arr.length === 0) {
        alert('Pilih minimal 1 baris terlebih dahulu.');
        return false;
      }

      const lines = arr.map(x => '  • ID ' + x.id + ' — ' + x.label).join('\n');
      const total = arr.length;
      const msg =
        `Akan menghapus ${total} baris dari tabel "${label}".\n\n` +
        `Daftar baris:\n${lines}\n\n` +
        `Tindakan ini bisa dipulihkan dari database (soft-delete), tetapi\n` +
        `tidak akan terlihat di halaman ini. Lanjutkan?`;

      return confirm(msg);
    };

    // Initial state — no rows selected.
    document.addEventListener('DOMContentLoaded', () => {
      ['analisa','situasi','rs','puskesmas'].forEach(k => __bulkRefresh(k));
    });
  </script>
</body>
</html>