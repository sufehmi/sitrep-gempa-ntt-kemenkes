<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah User — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen px-4 py-8" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-md mx-auto">
    <a href="{{ route('users.index') }}" class="text-sm text-teal-900 hover:underline">← Kembali</a>
    <h1 class="text-2xl font-semibold text-slate-800 mt-2 mb-6">+ Tambah User</h1>

    @if($errors->any())
      <div class="bg-coral-50 text-coral-500 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}" class="bg-white rounded-xl p-6 shadow-sm space-y-3">
      @csrf
      <div>
        <label class="block text-sm text-slate-600 mb-1">Username</label>
        <input type="text" name="username" required minlength="3" maxlength="64" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Nama (opsional)</label>
        <input type="text" name="name" maxlength="255" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Email (opsional)</label>
        <input type="email" name="email" maxlength="255" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Password (min 6 karakter)</label>
        <input type="password" name="password" required minlength="6" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required minlength="6" class="w-full border border-mint-200 rounded-lg px-3 py-2">
      </div>
      <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded-lg hover:bg-teal-800">Simpan</button>
    </form>
  </div>
</body>
</html>
