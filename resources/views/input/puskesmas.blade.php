<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Input Puskesmas — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen px-4 py-8" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-2xl mx-auto">
    <a href="{{ route('input.index') }}" class="text-sm text-teal-900 hover:underline">← Kembali</a>
    <h1 class="text-2xl font-semibold text-slate-800 mt-2 mb-6">⚕️ Input Data Puskesmas</h1>

    @if($errors->any())
      <div class="bg-coral-50 text-coral-500 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('input.puskesmas.store') }}" class="bg-white rounded-xl p-6 shadow-sm space-y-3">
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
        <label class="block text-sm text-slate-600 mb-1">Nama Puskesmas</label>
        <input type="text" name="nama_puskesmas" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Tanggal</label>
        <input type="date" name="tanggal" value="2026-08-18" required class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div class="grid grid-cols-4 gap-3">
        <div>
          <label class="block text-xs text-coral-500 font-bold mb-1">MERAH</label>
          <input type="number" name="merah" min="0" placeholder="0" class="w-full border-2 border-coral-500 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-xs text-amber-500 font-bold mb-1">KUNING</label>
          <input type="number" name="kuning" min="0" placeholder="0" class="w-full border-2 border-amber-500 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-xs text-sage-500 font-bold mb-1">HIJAU</label>
          <input type="number" name="hijau" min="0" placeholder="0" class="w-full border-2 border-sage-500 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-xs text-slate-800 font-bold mb-1">HITAM</label>
          <input type="number" name="hitam" min="0" placeholder="0" class="w-full border-2 border-slate-800 rounded-lg px-3 py-2">
        </div>
      </div>
      <p class="text-xs text-slate-500">Isi minimal salah satu kolom. Kolom yang kosong dianggap 0.</p>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Diagnosis/Catatan (opsional)</label>
        <textarea name="diagnosis" rows="3" class="w-full border border-mint-200 rounded-lg px-3 py-2"></textarea>
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Sumber Data (opsional)</label>
        <input type="text" name="sumber_data" maxlength="255" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded-lg hover:bg-teal-800">Simpan</button>
    </form>
  </div>
</body>
</html>
