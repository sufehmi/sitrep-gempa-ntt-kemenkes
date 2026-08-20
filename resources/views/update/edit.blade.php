<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit {{ $tableLabel }} — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen px-4 py-8" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-2xl mx-auto">
    <a href="{{ route('update.index') }}" class="text-sm text-teal-900 hover:underline">← Kembali ke daftar</a>
    <h1 class="text-2xl font-semibold text-slate-800 mt-2 mb-6">Edit {{ $tableLabel }}</h1>

    @if($errors->any())
      <div class="bg-red-50 text-red-600 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('update.update', ['table' => $table, 'id' => $record->id]) }}" class="bg-white rounded-xl p-6 shadow-sm space-y-3">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm text-slate-600 mb-1">Kabupaten</label>
        <select name="kabupaten_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
          @foreach($kabupaten as $k)
            <option value="{{ $k->id }}" @selected($record->kabupaten_id == $k->id)>{{ $k->nama_kabupaten }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm text-slate-600 mb-1">Tanggal</label>
        <input type="date" name="tanggal" value="{{ \Carbon\Carbon::parse($record->tanggal)->format('Y-m-d') }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
      </div>

      @if($table === 'analisa')
        <div>
          <label class="block text-sm text-slate-600 mb-1">Korban Luka</label>
          <input type="number" name="korban_luka" min="0" value="{{ $record->korban_luka }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Pasien RS</label>
          <input type="number" name="pasien_rs" min="0" value="{{ $record->pasien_rs }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Pasien Puskesmas</label>
          <input type="number" name="pasien_puskesmas" min="0" value="{{ $record->pasien_puskesmas }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Pola GAP</label>
          <input type="text" name="pola_gap" value="{{ $record->pola_gap }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Status</label>
          <input type="text" name="status" value="{{ $record->status }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Tindak Lanjut</label>
          <textarea name="tindak_lanjut" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2">{{ $record->tindak_lanjut }}</textarea>
        </div>
      @elseif($table === 'situasi')
        <div>
          <label class="block text-sm text-slate-600 mb-1">Waktu</label>
          <input type="text" name="waktu" value="{{ $record->waktu }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm text-slate-600 mb-1">Populasi Terdampak</label>
            <input type="number" name="populasi_terdampak" min="0" value="{{ $record->populasi_terdampak }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-1">Meninggal</label>
            <input type="number" name="meninggal" min="0" value="{{ $record->meninggal }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-1">Luka Berat</label>
            <input type="number" name="luka_berat" min="0" value="{{ $record->luka_berat }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-1">Luka Ringan</label>
            <input type="number" name="luka_ringan" min="0" value="{{ $record->luka_ringan }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-1">Pengungsi</label>
            <input type="number" name="pengungsi" min="0" value="{{ $record->pengungsi }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-1">Titik Pengungsian</label>
            <input type="number" name="titik_pengungsian" min="0" value="{{ $record->titik_pengungsian }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Sumber Data</label>
          <input type="text" name="sumber_data" value="{{ $record->sumber_data }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
      @else
        <div>
          <label class="block text-sm text-slate-600 mb-1">{{ $table === 'rs' ? 'Nama RS' : 'Nama Puskesmas' }}</label>
          <input type="text" name="{{ $table === 'rs' ? 'nama_rs' : 'nama_puskesmas' }}" value="{{ $table === 'rs' ? $record->nama_rs : $record->nama_puskesmas }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
        <div class="grid grid-cols-4 gap-3">
          <div>
            <label class="block text-xs text-red-500 font-bold mb-1">MERAH</label>
            <input type="number" name="merah" min="0" value="{{ $record->merah }}" placeholder="0" class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-xs text-amber-500 font-bold mb-1">KUNING</label>
            <input type="number" name="kuning" min="0" value="{{ $record->kuning }}" placeholder="0" class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-xs text-emerald-600 font-bold mb-1">HIJAU</label>
            <input type="number" name="hijau" min="0" value="{{ $record->hijau }}" placeholder="0" class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-xs text-slate-800 font-bold mb-1">HITAM</label>
            <input type="number" name="hitam" min="0" value="{{ $record->hitam }}" placeholder="0" class="w-full border border-slate-300 rounded-lg px-3 py-2">
          </div>
        </div>
        <p class="text-xs text-slate-500">Isi minimal salah satu. Kolom kosong dianggap 0.</p>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Diagnosis/Catatan</label>
          <textarea name="diagnosis" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2">{{ $record->diagnosis }}</textarea>
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Sumber Data</label>
          <input type="text" name="sumber_data" value="{{ $record->sumber_data }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>
      @endif

      <div class="flex gap-3 pt-3">
        <button type="submit" class="flex-1 bg-teal-900 text-white py-2 rounded-lg hover:bg-teal-800">Simpan Perubahan</button>
        <a href="{{ route('update.index') }}" class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg hover:bg-slate-300 text-center">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>
