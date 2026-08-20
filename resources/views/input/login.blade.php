<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { background: #EEF1F0; font-family: 'Inter', system-ui, sans-serif; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
  <div class="bg-white rounded-2xl shadow-sm p-8 w-full max-w-sm">
    <h1 class="text-xl font-semibold text-slate-800 mb-1">🔐 Login</h1>
    <p class="text-sm text-slate-400 mb-6">Sitrep Kesehatan Gempa Bumi NTT</p>

    @if($errors->any())
      <div class="bg-coral-50 text-coral-500 rounded-lg p-3 text-sm mb-4">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('input.login.submit') }}">
      @csrf
      <div class="mb-3">
        <label class="block text-sm text-slate-600 mb-1">Username</label>
        <input type="text" name="username" required autofocus class="w-full border border-mint-200 rounded-lg px-3 py-2 focus:outline-none focus:border-teal-900">
      </div>
      <div class="mb-4">
        <label class="block text-sm text-slate-600 mb-1">Password</label>
        <input type="password" name="password" required class="w-full border border-mint-200 rounded-lg px-3 py-2 focus:outline-none focus:border-teal-900">
      </div>
      <label class="flex items-center gap-2 text-sm text-slate-600 mb-4">
        <input type="checkbox" name="remember"> Ingat saya
      </label>
      <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded-lg hover:bg-teal-800">Login</button>
    </form>
    <div class="text-center mt-4 text-sm text-slate-400">
      <a href="{{ route('dashboard') }}">← Kembali ke dashboard</a>
    </div>
  </div>
</body>
</html>
