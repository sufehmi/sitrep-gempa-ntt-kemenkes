<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Docs · Dashboard Gempa NTT</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E📡%3C/text%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b1220;
            --bg-soft: #0f172a;
            --panel: #111c33;
            --panel-2: #162241;
            --border: #1f2d4d;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --brand: #14b8a6;
            --brand-soft: rgba(20, 184, 166, 0.12);
            --get: #10b981;
            --post: #f59e0b;
            --del: #ef4444;
        }
        * { scrollbar-width: thin; scrollbar-color: #1f2d4d var(--bg); }
        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); }
        code, pre, .mono { font-family: 'JetBrains Mono', Menlo, monospace; }
        .gradient-text { background: linear-gradient(135deg, #14b8a6, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glow { box-shadow: 0 0 40px rgba(20, 184, 166, 0.15); }
        .endpoint-card { background: var(--panel); border: 1px solid var(--border); border-radius: 14px; transition: all 0.2s; }
        .endpoint-card:hover { border-color: rgba(20, 184, 166, 0.4); transform: translateY(-1px); }
        .method-badge { font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px; }
        .method-get { background: rgba(16, 185, 129, 0.15); color: var(--get); border: 1px solid rgba(16, 185, 129, 0.3); }
        .code-block { background: #0a0f1e; border: 1px solid var(--border); border-radius: 10px; padding: 16px 18px; overflow-x: auto; font-size: 13px; line-height: 1.6; }
        .code-block .comment { color: #64748b; font-style: italic; }
        .code-block .key { color: #38bdf8; }
        .code-block .str { color: #facc15; }
        .code-block .num { color: #f472b6; }
        .code-block .com { color: #14b8a6; }
        .code-block .flag { color: #c084fc; }
        .code-block .punct { color: #64748b; }
        .copy-btn { transition: all 0.15s; }
        .copy-btn:hover { background: var(--brand-soft); color: var(--brand); }
        .copy-btn.copied { background: rgba(16, 185, 129, 0.15); color: var(--get); }
        .toc-link { transition: all 0.15s; border-left: 2px solid transparent; }
        .toc-link:hover, .toc-link.active { color: var(--brand); border-left-color: var(--brand); background: var(--brand-soft); }
        details > summary { list-style: none; cursor: pointer; }
        details > summary::-webkit-details-marker { display: none; }
        details[open] .chev { transform: rotate(90deg); }
        .chev { transition: transform 0.2s; }
        .nav-pill { transition: all 0.15s; }
        .nav-pill:hover { background: var(--brand-soft); color: var(--brand); }
        .table-row:hover { background: rgba(20, 184, 166, 0.04); }
        .input-key { background: var(--panel-2); border: 1px solid var(--border); color: var(--text); }
        .input-key:focus { outline: none; border-color: var(--brand); }
        .response-pre { max-height: 400px; overflow-y: auto; font-size: 12px; }
        .resp-status-200 { color: var(--get); }
        .resp-status-401 { color: var(--del); }
        .resp-status-404 { color: var(--post); }
    </style>
</head>
<body class="min-h-screen">

    {{-- TOP NAV --}}
    <header class="sticky top-0 z-50 backdrop-blur-md bg-[#0b1220]/80 border-b border-slate-800/60">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center font-bold text-slate-900">N</div>
                <div>
                    <div class="font-bold text-sm">NTT Gempa API</div>
                    <div class="text-[10px] text-slate-500 uppercase tracking-wider">v1 · Public Read-Only</div>
                </div>
            </a>
            <nav class="hidden md:flex items-center gap-1 text-sm">
                <a href="#endpoints" class="nav-pill px-3 py-1.5 rounded-md text-slate-300">Endpoints</a>
                <a href="#auth" class="nav-pill px-3 py-1.5 rounded-md text-slate-300">Auth</a>
                <a href="#try" class="nav-pill px-3 py-1.5 rounded-md text-slate-300">Try It</a>
                <a href="#errors" class="nav-pill px-3 py-1.5 rounded-md text-slate-300">Errors</a>
                <a href="{{ url('/') }}" class="ml-3 px-3 py-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs">
                    &larr; Dashboard
                </a>
            </nav>
        </div>
    </header>

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 opacity-30" style="background: radial-gradient(ellipse at top, rgba(20,184,166,0.2), transparent 60%);"></div>
        <div class="max-w-7xl mx-auto px-6 pt-16 pb-12 relative">
            <div class="flex items-center gap-2 mb-5">
                <span class="px-2.5 py-1 rounded-md bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-mono">v1.0</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-400 text-xs font-mono">REST · JSON</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-400 text-xs font-mono">No rate limit*</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">
                Dashboard Gempa NTT <span class="gradient-text">Public API</span>
            </h1>
            <p class="mt-5 text-lg text-slate-400 max-w-2xl">
                Akses data read-only untuk <strong class="text-slate-200">7 kabupaten terdampak gempa NTT</strong>:
                analisa harian, situasi kesehatan, kondisi pasien RS, dan Puskesmas.
                Buat API key di menu <a href="{{ url('/users') }}" class="text-teal-400 hover:underline">Manage User</a> untuk mulai.
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="#try" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-teal-500 hover:bg-teal-400 text-slate-900 font-semibold transition">
                    Try it now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="#endpoints" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium transition">
                    Lihat Endpoints
                </a>
            </div>

            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3 max-w-3xl">
                <div class="bg-slate-900/50 border border-slate-800 rounded-lg p-3">
                    <div class="text-2xl font-bold text-teal-400">7</div>
                    <div class="text-xs text-slate-500">Kabupaten</div>
                </div>
                <div class="bg-slate-900/50 border border-slate-800 rounded-lg p-3">
                    <div class="text-2xl font-bold text-teal-400">4</div>
                    <div class="text-xs text-slate-500">Tabel Data</div>
                </div>
                <div class="bg-slate-900/50 border border-slate-800 rounded-lg p-3">
                    <div class="text-2xl font-bold text-teal-400">GET</div>
                    <div class="text-xs text-slate-500">Read-Only</div>
                </div>
                <div class="bg-slate-900/50 border border-slate-800 rounded-lg p-3">
                    <div class="text-2xl font-bold text-teal-400">JSON</div>
                    <div class="text-xs text-slate-500">Response</div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-7xl mx-auto px-6 pb-24">

        <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-10">

            {{-- SIDEBAR TOC --}}
            <aside class="hidden lg:block">
                <div class="sticky top-20">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3 px-3">On this page</div>
                    <nav class="flex flex-col text-sm">
                        <a href="#base-url" class="toc-link px-3 py-1.5 rounded-r-md text-slate-400">Base URL</a>
                        <a href="#auth" class="toc-link px-3 py-1.5 rounded-r-md text-slate-400">Authentication</a>
                        <a href="#endpoints" class="toc-link px-3 py-1.5 rounded-r-md text-slate-400">Endpoints</a>
                        <a href="#kabupaten" class="toc-link pl-6 pr-3 py-1 rounded-r-md text-slate-500 text-xs">Kabupaten</a>
                        <a href="#analisa" class="toc-link pl-6 pr-3 py-1 rounded-r-md text-slate-500 text-xs">Analisa</a>
                        <a href="#situasi" class="toc-link pl-6 pr-3 py-1 rounded-r-md text-slate-500 text-xs">Situasi</a>
                        <a href="#rs" class="toc-link pl-6 pr-3 py-1 rounded-r-md text-slate-500 text-xs">RS</a>
                        <a href="#puskesmas" class="toc-link pl-6 pr-3 py-1 rounded-r-md text-slate-500 text-xs">Puskesmas</a>
                        <a href="#filters" class="toc-link px-3 py-1.5 rounded-r-md text-slate-400">Query Filters</a>
                        <a href="#response" class="toc-link px-3 py-1.5 rounded-r-md text-slate-400">Response Format</a>
                        <a href="#try" class="toc-link px-3 py-1.5 rounded-r-md text-slate-400">Try It Live</a>
                        <a href="#errors" class="toc-link px-3 py-1.5 rounded-r-md text-slate-400">Error Codes</a>
                    </nav>
                </div>
            </aside>

            {{-- CONTENT --}}
            <article class="min-w-0 space-y-16">

                {{-- BASE URL --}}
                <section id="base-url">
                    <h2 class="text-2xl font-bold mb-3">Base URL</h2>
                    <p class="text-slate-400 mb-4">Semua endpoint diakses dari base URL berikut:</p>
                    <div class="code-block flex items-center justify-between gap-3">
                        <code><span class="com">curl</span> <span class="flag">-H</span> <span class="str">"X-API-Key: YOUR_KEY"</span> \</code>
                    </div>
                    <div class="code-block mt-2 flex items-center justify-between gap-3">
                        <code><span class="str">https://ntt.tanggap-bencana.go.id/api/v1/</span></code>
                        <button class="copy-btn text-slate-500 px-2 py-1 rounded text-xs" data-copy="https://ntt.tanggap-bencana.go.id/api/v1/">Copy</button>
                    </div>
                </section>

                {{-- AUTH --}}
                <section id="auth">
                    <h2 class="text-2xl font-bold mb-3">Authentication</h2>
                    <p class="text-slate-400 mb-4">
                        Semua endpoint butuh <strong class="text-slate-200">API key</strong>. Buat key di menu
                        <a href="{{ url('/users') }}" class="text-teal-400 hover:underline">Manage User</a> dengan SHA1 gate.
                        Key dikirim via <strong class="text-slate-200">HTTP header</strong> (recommended) atau query string.
                    </p>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="endpoint-card p-5">
                            <div class="text-xs uppercase tracking-wider text-teal-400 mb-2 font-semibold">Header (recommended)</div>
                            <div class="code-block !p-3 mt-2">
                                <code><span class="flag">-H</span> <span class="str">"X-API-Key: ntt_a1b2c3d4..."</span></code>
                            </div>
                        </div>
                        <div class="endpoint-card p-5">
                            <div class="text-xs uppercase tracking-wider text-amber-400 mb-2 font-semibold">Query String (fallback)</div>
                            <div class="code-block !p-3 mt-2">
                                <code><span class="str">?api_key=ntt_a1b2c3d4...</span></code>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 rounded-lg border border-amber-500/30 bg-amber-500/5 text-amber-200/90 text-sm">
                        <strong>⚠ Perhatian:</strong> Key hanya ditampilkan <strong>sekali</strong> saat dibuat.
                        Disimpan sebagai SHA-256 hash di server. Kalau hilang, hapus & buat ulang.
                    </div>
                </section>

                {{-- ENDPOINTS --}}
                <section id="endpoints">
                    <h2 class="text-2xl font-bold mb-6">Endpoints</h2>

                    {{-- META --}}
                    <div id="meta" class="endpoint-card p-6 mb-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-base md:text-lg text-slate-100">/api/v1/</code>
                            </div>
                            <span class="text-xs text-slate-500">Public</span>
                        </div>
                        <p class="text-slate-400 mt-3 text-sm">Metadata API + daftar semua endpoint yang tersedia.</p>
                    </div>

                    {{-- KABUPATEN --}}
                    <div id="kabupaten" class="endpoint-card p-6 mb-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-base md:text-lg text-slate-100">/api/v1/kabupaten</code>
                            </div>
                            <span class="text-xs text-slate-500">Auth required</span>
                        </div>
                        <p class="text-slate-400 mt-3 text-sm">Daftar 7 kabupaten terdampak gempa NTT.</p>

                        <details class="mt-4">
                            <summary class="text-sm text-teal-400 hover:text-teal-300 flex items-center gap-1">
                                <svg class="chev w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                Lihat contoh response
                            </summary>
                            <div class="code-block mt-3 relative">
                                <pre><code><span class="punct">{</span>
  <span class="key">"kabupaten"</span><span class="punct">:</span> <span class="punct">[</span>
    <span class="punct">{</span>
      <span class="key">"id"</span><span class="punct">:</span> <span class="num">67</span><span class="punct">,</span>
      <span class="key">"nama_kabupaten"</span><span class="punct">:</span> <span class="str">"Sikka"</span><span class="punct">,</span>
      <span class="key">"nama_lengkap"</span><span class="punct">:</span> <span class="str">"Kabupaten Sikka"</span>
    <span class="punct">},</span>
    <span class="punct">{</span>
      <span class="key">"id"</span><span class="punct">:</span> <span class="num">68</span><span class="punct">,</span>
      <span class="key">"nama_kabupaten"</span><span class="punct">:</span> <span class="str">"Manggarai Timur"</span><span class="punct">,</span>
      <span class="key">"nama_lengkap"</span><span class="punct">:</span> <span class="str">"Kabupaten Manggarai Timur"</span>
    <span class="punct">}</span>
    <span class="punct">]</span><span class="punct">,</span>
  <span class="key">"meta"</span><span class="punct">:</span> <span class="punct">{</span>
    <span class="key">"count"</span><span class="punct">:</span> <span class="num">7</span><span class="punct">,</span>
    <span class="key">"generated_at"</span><span class="punct">:</span> <span class="str">"2026-08-19T12:00:00Z"</span>
  <span class="punct">}</span>
<span class="punct">}</span></code></pre>
                            </div>
                        </details>
                    </div>

                    {{-- ANALISA --}}
                    <div id="analisa" class="endpoint-card p-6 mb-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-base md:text-lg text-slate-100">/api/v1/analisa</code>
                            </div>
                            <span class="text-xs text-slate-500">Auth required</span>
                        </div>
                        <p class="text-slate-400 mt-3 text-sm">Data analisa ringkasan harian per kabupaten.</p>

                        <div class="mt-4">
                            <div class="text-xs uppercase tracking-wider text-slate-500 mb-2">Filter</div>
                            <div class="flex flex-wrap gap-2">
                                <code class="px-2 py-1 rounded bg-slate-800 text-xs text-slate-300">kabupaten_id=67</code>
                                <code class="px-2 py-1 rounded bg-slate-800 text-xs text-slate-300">tanggal=2026-08-19</code>
                            </div>
                        </div>

                        <details class="mt-4">
                            <summary class="text-sm text-teal-400 hover:text-teal-300 flex items-center gap-1">
                                <svg class="chev w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                Lihat contoh response
                            </summary>
                            <div class="code-block mt-3">
                                <pre><code><span class="punct">{</span>
  <span class="key">"data"</span><span class="punct">:</span> <span class="punct">[</span>
    <span class="punct">{</span>
      <span class="key">"id"</span><span class="punct">:</span> <span class="num">70</span><span class="punct">,</span>
      <span class="key">"kabupaten_id"</span><span class="punct">:</span> <span class="num">67</span><span class="punct">,</span>
      <span class="key">"kabupaten"</span><span class="punct">:</span> <span class="str">"Sikka"</span><span class="punct">,</span>
      <span class="key">"tanggal"</span><span class="punct">:</span> <span class="str">"2026-08-19"</span><span class="punct">,</span>
      <span class="key">"luka_ringan"</span><span class="punct">:</span> <span class="num">5</span><span class="punct">,</span>
      <span class="key">"luka_berat"</span><span class="punct">:</span> <span class="num">0</span><span class="punct">,</span>
      <span class="key">"meninggal"</span><span class="punct">:</span> <span class="num">0</span><span class="punct">,</span>
      <span class="key">"pengungsi"</span><span class="punct">:</span> <span class="num">1240</span><span class="punct">,</span>
      <span class="key">"rumah_rusak"</span><span class="punct">:</span> <span class="num">38</span>
    <span class="punct">}</span>
  <span class="punct">]</span><span class="punct">,</span>
  <span class="key">"meta"</span><span class="punct">:</span> <span class="punct">{</span> <span class="key">"count"</span><span class="punct">:</span> <span class="num">1</span><span class="punct">,</span> <span class="key">"generated_at"</span><span class="punct">:</span> <span class="str">"..."</span> <span class="punct">}</span>
<span class="punct">}</span></code></pre>
                            </div>
                        </details>
                    </div>

                    {{-- SITUASI --}}
                    <div id="situasi" class="endpoint-card p-6 mb-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-base md:text-lg text-slate-100">/api/v1/situasi</code>
                            </div>
                            <span class="text-xs text-slate-500">Auth required</span>
                        </div>
                        <p class="text-slate-400 mt-3 text-sm">Situasi kesehatan &amp; populasi terdampak.</p>

                        <div class="mt-4">
                            <div class="text-xs uppercase tracking-wider text-slate-500 mb-2">Filter</div>
                            <div class="flex flex-wrap gap-2">
                                <code class="px-2 py-1 rounded bg-slate-800 text-xs text-slate-300">kabupaten_id</code>
                                <code class="px-2 py-1 rounded bg-slate-800 text-xs text-slate-300">tanggal</code>
                            </div>
                        </div>
                    </div>

                    {{-- RS --}}
                    <div id="rs" class="endpoint-card p-6 mb-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-base md:text-lg text-slate-100">/api/v1/rs</code>
                            </div>
                            <span class="text-xs text-slate-500">Auth required</span>
                        </div>
                        <p class="text-slate-400 mt-3 text-sm">Kondisi pasien rawat inap di Rumah Sakit (kategori: merah/kuning/hijau/hitam).</p>
                    </div>

                    {{-- PUSKESMAS --}}
                    <div id="puskesmas" class="endpoint-card p-6 mb-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-base md:text-lg text-slate-100">/api/v1/puskesmas</code>
                            </div>
                            <span class="text-xs text-slate-500">Auth required</span>
                        </div>
                        <p class="text-slate-400 mt-3 text-sm">Kondisi pasien rawat jalan di Puskesmas (kategori: merah/kuning/hijau/hitam).</p>
                    </div>
                </section>

                {{-- FILTERS --}}
                <section id="filters">
                    <h2 class="text-2xl font-bold mb-3">Query Filters</h2>
                    <p class="text-slate-400 mb-4">Semua endpoint data mendukung 2 filter opsional:</p>
                    <div class="overflow-x-auto rounded-lg border border-slate-800">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-900/50 text-slate-400 text-left">
                                <tr>
                                    <th class="px-4 py-2.5 font-semibold">Parameter</th>
                                    <th class="px-4 py-2.5 font-semibold">Tipe</th>
                                    <th class="px-4 py-2.5 font-semibold">Contoh</th>
                                    <th class="px-4 py-2.5 font-semibold">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                <tr class="table-row">
                                    <td class="px-4 py-2.5"><code class="text-teal-300">kabupaten_id</code></td>
                                    <td class="px-4 py-2.5 text-slate-400">integer</td>
                                    <td class="px-4 py-2.5"><code class="text-slate-300">67</code></td>
                                    <td class="px-4 py-2.5 text-slate-400">Filter per kabupaten (lihat <code>/api/v1/kabupaten</code>)</td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-4 py-2.5"><code class="text-teal-300">tanggal</code></td>
                                    <td class="px-4 py-2.5 text-slate-400">date</td>
                                    <td class="px-4 py-2.5"><code class="text-slate-300">2026-08-19</code></td>
                                    <td class="px-4 py-2.5 text-slate-400">Format YYYY-MM-DD. Exact match.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- RESPONSE FORMAT --}}
                <section id="response">
                    <h2 class="text-2xl font-bold mb-3">Response Format</h2>
                    <p class="text-slate-400 mb-4">Setiap response JSON punya 2 key utama:</p>
                    <div class="code-block">
<pre><code><span class="punct">{</span>
  <span class="key">"data"</span>      <span class="punct">:</span> <span class="punct">[</span> <span class="punct">{</span> <span class="key">"id"</span><span class="punct">:</span> <span class="num">1</span><span class="punct">,</span> <span class="key">"..."</span><span class="punct">:</span> <span class="str">"..."</span> <span class="punct">}</span> <span class="punct">]</span><span class="punct">,</span>    <span class="comment">// atau "kabupaten"</span>
  <span class="key">"meta"</span>      <span class="punct">:</span> <span class="punct">{</span>
    <span class="key">"count"</span>       <span class="punct">:</span> <span class="num">7</span><span class="punct">,</span>
    <span class="key">"generated_at"</span><span class="punct">:</span> <span class="str">"2026-08-19T12:00:00+07:00"</span><span class="punct">,</span>
    <span class="key">"filters"</span>     <span class="punct">:</span> <span class="punct">{</span> <span class="key">"kabupaten_id"</span><span class="punct">:</span> <span class="num">67</span><span class="punct">,</span> <span class="key">"tanggal"</span><span class="punct">:</span> <span class="str">"2026-08-19"</span> <span class="punct">}</span>
  <span class="punct">}</span>
<span class="punct">}</span></code></pre>
                    </div>
                </section>

                {{-- TRY IT LIVE --}}
                <section id="try">
                    <h2 class="text-2xl font-bold mb-3">Try It Live</h2>
                    <p class="text-slate-400 mb-4">Masukkan API key kamu lalu klik endpoint untuk test:</p>

                    <div class="endpoint-card p-5">
                        <label class="text-xs uppercase tracking-wider text-slate-500 mb-2 block">API Key</label>
                        <input id="apiKeyInput" type="text" placeholder="ntt_a1b2c3d4..." class="input-key w-full px-3 py-2.5 rounded-lg mono text-sm" />
                        <p class="text-xs text-slate-500 mt-2">
                            Belum punya key? Buat di <a href="{{ url('/users') }}" class="text-teal-400 hover:underline">Manage User</a> (gate SHA1).
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mt-5" id="endpoints-try">
                        <button class="endpoint-card p-4 text-left try-btn" data-endpoint="kabupaten">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-sm text-slate-100">/api/v1/kabupaten</code>
                            </div>
                            <div class="text-xs text-slate-500">Daftar 7 kabupaten</div>
                        </button>
                        <button class="endpoint-card p-4 text-left try-btn" data-endpoint="analisa">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-sm text-slate-100">/api/v1/analisa</code>
                            </div>
                            <div class="text-xs text-slate-500">Analisa harian</div>
                        </button>
                        <button class="endpoint-card p-4 text-left try-btn" data-endpoint="situasi">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-sm text-slate-100">/api/v1/situasi</code>
                            </div>
                            <div class="text-xs text-slate-500">Situasi kesehatan</div>
                        </button>
                        <button class="endpoint-card p-4 text-left try-btn" data-endpoint="rs">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-sm text-slate-100">/api/v1/rs</code>
                            </div>
                            <div class="text-xs text-slate-500">Pasien RS</div>
                        </button>
                        <button class="endpoint-card p-4 text-left try-btn md:col-span-2" data-endpoint="puskesmas">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="method-badge method-get">GET</span>
                                <code class="text-sm text-slate-100">/api/v1/puskesmas</code>
                            </div>
                            <div class="text-xs text-slate-500">Pasien Puskesmas</div>
                        </button>
                    </div>

                    <div id="responseBox" class="endpoint-card p-5 mt-5 hidden">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <div class="text-xs uppercase tracking-wider text-slate-500">Response</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span id="respStatus" class="text-sm font-mono font-bold">200</span>
                                    <span id="respTime" class="text-xs text-slate-500"></span>
                                </div>
                            </div>
                            <button id="copyResp" class="copy-btn text-slate-500 px-3 py-1.5 rounded text-xs">Copy JSON</button>
                        </div>
                        <pre id="respBody" class="response-pre code-block !p-4 text-xs"></pre>
                    </div>
                </section>

                {{-- ERRORS --}}
                <section id="errors">
                    <h2 class="text-2xl font-bold mb-3">Error Codes</h2>
                    <div class="overflow-x-auto rounded-lg border border-slate-800">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-900/50 text-slate-400 text-left">
                                <tr>
                                    <th class="px-4 py-2.5 font-semibold">HTTP</th>
                                    <th class="px-4 py-2.5 font-semibold">Error Code</th>
                                    <th class="px-4 py-2.5 font-semibold">Arti</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                <tr class="table-row">
                                    <td class="px-4 py-2.5"><span class="text-emerald-400 font-mono font-bold">200</span></td>
                                    <td class="px-4 py-2.5 text-slate-400">—</td>
                                    <td class="px-4 py-2.5 text-slate-300">Sukses</td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-4 py-2.5"><span class="text-amber-400 font-mono font-bold">401</span></td>
                                    <td class="px-4 py-2.5 text-slate-400"><code>missing_api_key</code></td>
                                    <td class="px-4 py-2.5 text-slate-300">Header <code>X-API-Key</code> / query <code>api_key</code> tidak ada</td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-4 py-2.5"><span class="text-amber-400 font-mono font-bold">401</span></td>
                                    <td class="px-4 py-2.5 text-slate-400"><code>invalid_api_key</code></td>
                                    <td class="px-4 py-2.5 text-slate-300">Key salah, sudah dihapus, atau dinonaktifkan</td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-4 py-2.5"><span class="text-red-400 font-mono font-bold">404</span></td>
                                    <td class="px-4 py-2.5 text-slate-400"><code>not_found</code></td>
                                    <td class="px-4 py-2.5 text-slate-300">Endpoint tidak ada</td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-4 py-2.5"><span class="text-red-400 font-mono font-bold">500</span></td>
                                    <td class="px-4 py-2.5 text-slate-400"><code>server_error</code></td>
                                    <td class="px-4 py-2.5 text-slate-300">Server error (hubungi admin)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 p-4 rounded-lg bg-slate-900/40 border border-slate-800 text-sm text-slate-400">
                        <strong class="text-slate-200">Contoh error response:</strong>
                        <div class="code-block mt-3">
                            <pre><code><span class="punct">{</span>
  <span class="key">"error"</span><span class="punct">:</span> <span class="str">"invalid_api_key"</span><span class="punct">,</span>
  <span class="key">"message"</span><span class="punct">:</span> <span class="str">"API key tidak valid atau sudah dinonaktifkan."</span>
<span class="punct">}</span></code></pre>
                        </div>
                    </div>
                </section>

                <footer class="border-t border-slate-800 pt-8 text-center text-sm text-slate-500">
                    Dashboard Gempa NTT &middot; Public API v1.0 &middot; &copy; 2026 Pokja RCCE
                </footer>

            </article>
        </div>
    </main>

    <script>
        // === Copy buttons ===
        document.querySelectorAll('.copy-btn[data-copy]').forEach(btn => {
            btn.addEventListener('click', () => {
                navigator.clipboard.writeText(btn.dataset.copy);
                const orig = btn.textContent;
                btn.textContent = 'Copied!';
                btn.classList.add('copied');
                setTimeout(() => { btn.textContent = orig; btn.classList.remove('copied'); }, 1200);
            });
        });

        // === TOC active link ===
        const tocLinks = document.querySelectorAll('.toc-link');
        const sections = [...tocLinks].map(l => document.querySelector(l.getAttribute('href'))).filter(Boolean);
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    tocLinks.forEach(l => l.classList.remove('active'));
                    const link = document.querySelector('.toc-link[href="#' + e.target.id + '"]');
                    if (link) link.classList.add('active');
                }
            });
        }, { rootMargin: '-30% 0px -60% 0px' });
        sections.forEach(s => obs.observe(s));

        // === Try It ===
        const BASE = 'https://ntt.tanggap-bencana.go.id/api/v1';
        const apiKeyInput = document.getElementById('apiKeyInput');
        const responseBox = document.getElementById('responseBox');
        const respStatus = document.getElementById('respStatus');
        const respTime = document.getElementById('respTime');
        const respBody = document.getElementById('respBody');
        const copyResp = document.getElementById('copyResp');

        // Restore saved key
        const saved = localStorage.getItem('ntt_api_key');
        if (saved) apiKeyInput.value = saved;
        apiKeyInput.addEventListener('change', () => localStorage.setItem('ntt_api_key', apiKeyInput.value.trim()));

        document.querySelectorAll('.try-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const key = apiKeyInput.value.trim();
                const ep = btn.dataset.endpoint;
                if (!key) {
                    responseBox.classList.remove('hidden');
                    respStatus.textContent = '401';
                    respStatus.className = 'text-sm font-mono font-bold resp-status-401';
                    respBody.textContent = JSON.stringify({ error: 'missing_api_key', message: 'Masukkan API key dulu.' }, null, 2);
                    return;
                }
                respBody.textContent = 'Loading...';
                responseBox.classList.remove('hidden');
                respStatus.textContent = '...';
                respStatus.className = 'text-sm font-mono font-bold text-slate-500';
                const t0 = performance.now();
                try {
                    const r = await fetch(`${BASE}/${ep}`, { headers: { 'X-API-Key': key } });
                    const dt = Math.round(performance.now() - t0);
                    const text = await r.text();
                    respStatus.textContent = r.status;
                    respStatus.className = 'text-sm font-mono font-bold ' + (r.status === 200 ? 'resp-status-200' : r.status === 401 ? 'resp-status-401' : 'resp-status-404');
                    respTime.textContent = `· ${dt}ms · ${r.headers.get('content-length') || '?'} bytes`;
                    try { respBody.textContent = JSON.stringify(JSON.parse(text), null, 2); }
                    catch { respBody.textContent = text; }
                } catch (e) {
                    respStatus.textContent = 'ERR';
                    respStatus.className = 'text-sm font-mono font-bold resp-status-401';
                    respBody.textContent = e.message;
                }
            });
        });

        copyResp.addEventListener('click', () => {
            navigator.clipboard.writeText(respBody.textContent);
            const orig = copyResp.textContent;
            copyResp.textContent = 'Copied!';
            copyResp.classList.add('copied');
            setTimeout(() => { copyResp.textContent = orig; copyResp.classList.remove('copied'); }, 1200);
        });
    </script>
</body>
</html>
