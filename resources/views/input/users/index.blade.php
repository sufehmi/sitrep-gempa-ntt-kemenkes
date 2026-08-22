<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage User — Sitrep NTT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen px-4 py-8" style="background:#EEF1F0;font-family:Inter,system-ui,sans-serif;">
  <div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold text-slate-800">⚙️ Manage User</h1>
      <form method="POST" action="{{ route('users.lock') }}">
        @csrf
        <button type="submit" class="text-sm text-coral-500 hover:underline">🔒 Kunci</button>
      </form>
    </div>

    @if(session('status'))
      <div class="bg-sage-50 text-sage-500 rounded-lg p-3 mb-4 text-sm">{{ session('status') }}</div>
    @endif
    @if(session('new_api_key'))
      <div class="bg-amber-50 border border-amber-300 rounded-lg p-4 mb-4 text-sm">
        <p class="font-semibold text-amber-800 mb-2">API key baru: <span class="font-mono">{{ session('new_api_key.name') }}</span></p>
        <p class="text-amber-700 mb-2">⚠️ Salin sekarang — key ini hanya ditampilkan sekali dan tidak bisa dilihat lagi.</p>
        <div class="flex items-center gap-2">
          <code id="newkey" class="flex-1 bg-white border border-amber-300 rounded px-3 py-2 font-mono text-xs break-all">{{ session('new_api_key.plain') }}</code>
          <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('newkey').innerText); this.innerText='✓ Tersalin';" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 rounded text-xs whitespace-nowrap">Salin</button>
        </div>
        <p class="text-xs text-amber-600 mt-2">Cara pakai: kirim via header <code>X-API-Key: {{ session('new_api_key.plain') }}</code></p>
      </div>
    @endif
    @if($errors->any())
      <div class="bg-coral-50 text-coral-500 rounded-lg p-3 mb-4 text-sm">
        <ul class="m-0 pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <a href="{{ route('users.create') }}" class="inline-block bg-teal-900 text-white px-4 py-2 rounded-lg hover:bg-teal-800 mb-4">+ Tambah User</a>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
      <table class="w-full">
        <thead class="bg-mint-50">
          <tr>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">ID</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Username</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Nama</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Email</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Dibuat</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $u)
            <tr class="border-t border-mint-200">
              <td class="px-4 py-2 text-sm">{{ $u->id }}</td>
              <td class="px-4 py-2 text-sm font-semibold">{{ $u->username }}</td>
              <td class="px-4 py-2 text-sm">{{ $u->name ?? '-' }}</td>
              <td class="px-4 py-2 text-sm text-slate-400">{{ $u->email ?? '-' }}</td>
              <td class="px-4 py-2 text-sm text-slate-400">{{ $u->created_at->format('Y-m-d') }}</td>
              <td class="px-4 py-2 text-sm">
                @if(auth()->id() !== $u->id)
                  <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus user {{ $u->username }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-coral-500 hover:underline">Hapus</button>
                  </form>
                @else
                  <span class="text-slate-300">(current)</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <h2 class="text-xl font-semibold text-slate-800 mb-3">🔑 API Key</h2>
    <p class="text-sm text-slate-500 mb-3">API key digunakan untuk akses read-only ke endpoint <code class="bg-slate-100 px-1 rounded">/api/v1/*</code> via header <code class="bg-slate-100 px-1 rounded">X-API-Key</code>.</p>

    <form method="POST" action="{{ route('users.api-keys.store') }}" class="bg-white rounded-xl shadow-sm p-4 mb-4 flex gap-3">
      @csrf
      <input type="text" name="name" required minlength="3" maxlength="100" placeholder="Nama key (mis. Portal Eksternal)" class="flex-1 border border-slate-300 rounded-lg px-3 py-2 text-sm">
      <button type="submit" class="bg-teal-900 hover:bg-teal-800 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap">+ Buat API Key</button>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <table class="w-full">
        <thead class="bg-mint-50">
          <tr>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">ID</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Nama</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Prefix</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Pemakaian</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Terakhir Dipakai</th>
            <th class="text-left px-4 py-2 text-xs uppercase text-slate-600">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($apiKeys as $k)
            <tr class="border-t border-mint-200">
              <td class="px-4 py-2 text-sm">{{ $k->id }}</td>
              <td class="px-4 py-2 text-sm font-semibold">{{ $k->name }}</td>
              <td class="px-4 py-2 text-sm font-mono text-slate-500">{{ $k->prefix }}…</td>
              <td class="px-4 py-2 text-sm">{{ number_format($k->usage_count) }}x</td>
              <td class="px-4 py-2 text-sm text-slate-400">{{ $k->last_used_at?->diffForHumans() ?? 'belum pernah' }}</td>
              <td class="px-4 py-2 text-sm">
                <form method="POST" action="{{ route('users.api-keys.destroy', $k) }}" onsubmit="return confirm('Hapus API key {{ $k->name }}? Pemakai akan kehilangan akses segera.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-coral-500 hover:underline">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-3 text-sm text-slate-400 text-center">Belum ada API key.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <a href="{{ route('input.index') }}" class="inline-block mt-4 text-sm text-teal-900 hover:underline">← Kembali</a>
  </div>
</body>
</html>