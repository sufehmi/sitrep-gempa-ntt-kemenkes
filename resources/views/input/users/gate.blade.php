<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage User Gate — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen px-4 py-8" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-md mx-auto">
    <a href="{{ route('input.index') }}" class="text-sm text-teal-900 hover:underline">← Kembali</a>
    <h1 class="text-2xl font-semibold text-slate-800 mt-2 mb-1">⚙️ Manage User — SHA1 Gate</h1>
    <p class="text-sm text-slate-400 mb-6">Masukkan 40 karakter hash SHA1 (huruf a–f dan angka 0–9) untuk membuka halaman Manage User.</p>

    @if($errors->any())
      <div class="bg-coral-50 text-coral-500 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('users.gate.submit') }}" class="bg-white rounded-xl p-6 shadow-sm">
      @csrf
      <label class="block text-sm text-slate-600 mb-1">SHA1 Hash</label>
      <input type="password" name="manage_password" required maxlength="40" pattern="[a-fA-F0-9]{40}"
             placeholder="••••••••••••••••••••••••••••••••••••••••"
             class="w-full border border-mint-200 rounded-lg px-3 py-2 font-mono text-sm focus:outline-none focus:border-teal-900">
      <small class="text-xs text-slate-400 d-block mb-3">
        Masukkan 40 karakter hash SHA1 (huruf a–f dan angka 0–9).
      </small>
      <button type="submit" class="w-full bg-amber-500 text-white py-2 rounded-lg hover:bg-amber-600">🔓 Buka Manage User</button>
    </form>
  </div>
</body>
</html>
