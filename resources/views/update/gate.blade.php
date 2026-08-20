<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Akses Update — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center px-4" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-md w-full bg-white rounded-xl shadow-sm p-8">
    <h1 class="text-2xl font-semibold text-slate-800 mb-2">Akses Update Data</h1>
    <p class="text-sm text-slate-500 mb-6">Masukkan SHA1 hash untuk membuka halaman edit & hapus.</p>

    @if($errors->any())
      <div class="bg-red-50 text-red-600 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('update.gate.submit') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm text-slate-600 mb-1">Password (SHA1 hash 40 karakter hex)</label>
        <input type="password" name="manage_password" required autofocus
               pattern="[a-fA-F0-9]{40}" maxlength="40"
               class="w-full border border-slate-300 rounded-lg px-3 py-2 font-mono">
      </div>
      <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded-lg hover:bg-teal-800">Buka Update</button>
    </form>

    <div class="mt-6 text-center">
      <a href="{{ route('dashboard') }}" class="text-sm text-slate-500 hover:text-teal-900">← Kembali ke dashboard</a>
    </div>
  </div>
</body>
</html>
