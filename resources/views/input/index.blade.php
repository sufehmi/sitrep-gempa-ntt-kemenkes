<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Input Data — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { background: #EEF1F0; font-family: 'Inter', system-ui, sans-serif; }
  </style>
</head>
<body class="min-h-screen px-4 py-8">
  <div class="max-w-4xl mx-auto">
    <a href="{{ route('dashboard') }}" class="text-sm text-teal-900 hover:underline">← Kembali ke dashboard</a>
    <h1 class="text-2xl font-semibold text-slate-800 mt-2 mb-1">Input Data Harian</h1>
    <p class="text-sm text-slate-400 mb-6">Login sebagai <strong>{{ $user->username }}</strong> ({{ $user->name ?? '-' }})</p>

    @if(session('status'))
      <div class="bg-sage-50 text-sage-500 rounded-lg p-3 mb-4 text-sm">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
      <a href="{{ route('input.analisa') }}" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
        <div class="font-semibold text-slate-800">Input Analisa Ringkasan</div>
        <div class="text-xs text-slate-400 mt-1">Per kabupaten per tanggal</div>
      </a>
      <a href="{{ route('input.situasi') }}" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
        <div class="font-semibold text-slate-800">Input Situasi Kesehatan</div>
        <div class="text-xs text-slate-400 mt-1">Meninggal, luka, pengungsi, dll</div>
      </a>
      <a href="{{ route('input.rs') }}" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
        <div class="font-semibold text-slate-800">Input Data RS</div>
        <div class="text-xs text-slate-400 mt-1">Triase merah/kuning/hijau/hitam</div>
      </a>
      <a href="{{ route('input.puskesmas') }}" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
        <div class="font-semibold text-slate-800">Input Data Puskesmas</div>
        <div class="text-xs text-slate-400 mt-1">Triase per puskesmas</div>
      </a>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center justify-between gap-4">
      <div>
        <div class="text-sm text-slate-400">Tambah/hapus user yang dapat login</div>
        <div class="text-xs text-slate-400 mt-1">Perlu SHA1 hash Manage User</div>
      </div>
      <a href="{{ route('users.gate') }}" class="inline-flex items-center gap-2 bg-teal-900 hover:bg-teal-800 text-white font-semibold text-sm px-4 py-2 rounded-lg transition shrink-0">
        Manage User
        <span aria-hidden="true">&rarr;</span>
      </a>
    </div>

    <form method="POST" action="{{ route('input.logout') }}" class="mt-6">
      @csrf
      <button type="submit" class="text-coral-500 hover:underline text-sm">Logout</button>
    </form>
  </div>
</body>
</html>
