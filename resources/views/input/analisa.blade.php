<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Input Analisa — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen px-4 py-8" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-2xl mx-auto">
    <a href="{{ route('input.index') }}" class="text-sm text-teal-900 hover:underline">← Kembali</a>
    <h1 class="text-2xl font-semibold text-slate-800 mt-2 mb-6">📊 Input Analisa Ringkasan</h1>

    @if($errors->any())
      <div class="bg-coral-50 text-coral-500 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('input.analisa.store') }}" class="bg-white rounded-xl p-6 shadow-sm space-y-3">
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
      <div>
        <label class="block text-sm text-slate-600 mb-1">Tanggal</label>
        <input type="date" name="tanggal" value="2026-08-18" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="block text-sm text-slate-600 mb-1">Korban Luka</label>
          <input type="number" name="korban_luka" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Pasien RS</label>
          <input type="number" name="pasien_rs" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Pasien PKM</label>
          <input type="number" name="pasien_puskesmas" min="0" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
        </div>
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Pola Gap (opsional)</label>
        <input type="text" name="pola_gap" maxlength="255" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Status (opsional)</label>
        <input type="text" name="status" maxlength="255" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Tindak Lanjut (opsional)</label>
        <textarea name="tindak_lanjut" rows="3" class="w-full border border-mint-200 rounded-lg px-3 py-2"></textarea>
      </div>
      <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded-lg hover:bg-teal-800">Simpan</button>
    </form>
  </div>
</body>
</html>
