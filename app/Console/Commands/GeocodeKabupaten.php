<?php

namespace App\Console\Commands;

use App\Models\Kabupaten;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodeKabupaten extends Command
{
    protected $signature = 'kabupaten:geocode {--force : Overwrite existing coordinates}';
    protected $description = 'Fetch latitude/longitude for each kabupaten via Nominatim (OpenStreetMap)';

    /**
     * Daftar kabupaten terdampak Gempa NTT 2026.
     * Query Nominatim: {nama_kabupaten}, Nusa Tenggara Timur, Indonesia
     */
    private const KABUPATEN_LIST = [
        'Sikka'              => null,
        'Manggarai Timur'    => null,
        'Manggarai'          => null,
        'Ngada'              => null,
        'Nagekeo'            => null,
        'Ende'               => null,
        'Manggarai Barat'    => null,
    ];

    public function handle(): int
    {
        $force = $this->option('force');
        $userAgent = 'SitrepNTT/1.0 (https://ntt.tanggap-bencana.go.id; admin@tanggap-bencana.go.id)';

        $count = 0;
        foreach (self::KABUPATEN_LIST as $name => $_) {
            $kab = Kabupaten::where('nama_kabupaten', $name)->first();

            if (!$kab) {
                $this->warn("✗ Kabupaten '$name' tidak ditemukan di DB (skip)");
                continue;
            }

            if (!$force && $kab->latitude && $kab->longitude) {
                $this->line("→ $name: skip (sudah punya koordinat)");
                continue;
            }

            $query = $name . ', Nusa Tenggara Timur, Indonesia';
            $this->line("→ Geocoding: $query");

            try {
                $response = Http::withHeaders(['User-Agent' => $userAgent])
                    ->timeout(15)
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q'      => $query,
                        'format' => 'json',
                        'limit'  => 1,
                    ]);

                if ($response->failed()) {
                    $this->error("  HTTP " . $response->status());
                    continue;
                }

                $data = $response->json();
                if (empty($data[0]['lat']) || empty($data[0]['lon'])) {
                    $this->error("  Tidak ada hasil dari Nominatim");
                    Log::warning("Geocode failed for $name", ['response' => $data]);
                    continue;
                }

                $kab->latitude  = $data[0]['lat'];
                $kab->longitude = $data[0]['lon'];
                $kab->save();

                $this->info("  ✓ {$data[0]['lat']}, {$data[0]['lon']}");
                $count++;

                // Nominatim rate limit: max 1 request/detik
                sleep(1);
            } catch (\Throwable $e) {
                $this->error("  Exception: " . $e->getMessage());
                Log::error("Geocode exception for $name", ['e' => $e]);
            }
        }

        $this->newLine();
        $this->info("Selesai: $count kabupaten di-update.");

        return self::SUCCESS;
    }
}