<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Input Situasi — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen px-4 py-8" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-2xl mx-auto">
    <a href="{{ route('input.index') }}" class="text-sm text-teal-900 hover:underline">← Kembali</a>
    <h1 class="text-2xl font-semibold text-slate-800 mt-2 mb-6">🏥 Input Situasi Kesehatan</h1>

    @if($errors->any())
      <div class="bg-coral-50 text-coral-500 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('input.situasi.store') }}" class="bg-white rounded-xl p-6 shadow-sm space-y-3">
      @csrf
      <div>
        <label class="block text-sm text-slate-600 mb-1">Kabupaten</label>
        <select name="kabupaten_id" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
          <option value="">-- Pilih kabupaten --</option>
          @foreach($kabupaten as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kabupaten }}</option>
          @endforeach
        </select>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-slate-600 mb-1">Tanggal</label>
          <input type="date" name="tanggal" value="2026-08-18" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Waktu (mis. 17.00 WIB)</label>
          <input type="text" name="waktu" maxlength="32" value="00:01" class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Populasi Terdampak</label>
        <input type="number" name="populasi_terdampak" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-slate-600 mb-1">Meninggal</label>
          <input type="number" name="meninggal" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Luka Berat</label>
          <input type="number" name="luka_berat" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Luka Ringan</label>
          <input type="number" name="luka_ringan" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Pengungsi (jiwa)</label>
          <input type="number" name="pengungsi" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Titik Pengungsian</label>
          <input type="number" name="titik_pengungsian" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Sumber Data</label>
          <input type="text" name="sumber_data" maxlength="255" class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
      </div>
      <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded-lg hover:bg-teal-800">Simpan</button>
    </form>
  </div>
</body>
</html>
