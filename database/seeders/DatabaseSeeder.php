<?php

namespace Database\Seeders;

use App\Models\AnalisaRingkasan;
use App\Models\Kabupaten;
use App\Models\KondisiPasienPuskesmas;
use App\Models\KondisiPasienRs;
use App\Models\SituasiKesehatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;

class DatabaseSeeder extends Seeder
{
    private const EXCEL_PATH = '/home/umaruto/.hermes/cache/documents/doc_5f6ec0f6fa77_18Agustus_Dataset_Input_Dashboard_Gempa_NTT_18_Agustus_2026_TAB1_Sederhana_VALIDATED.xlsx';

    private const VALID_KABUPATEN = [
        'Sikka', 'Manggarai Timur', 'Manggarai', 'Ngada',
        'Nagekeo', 'Ende', 'Manggarai Barat',
    ];

    private function validKabupaten(string $name): bool
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));
        return in_array($name, self::VALID_KABUPATEN, true);
    }

    public function run(): void
    {
        // Kabupaten akan auto-create dari data Excel di seed*() methods.
        $this->importExcelSheet(
            'Analisa Ringkasan Harian',
            fn(array $row) => $this->seedAnalisa($row),
            'C4', 20
        );

        $this->importExcelSheet(
            'Situasi Kesehatan dan Populasi ',
            fn(array $row) => $this->seedSituasi($row),
            'C2', 20
        );

        $this->importExcelSheet(
            'Identifikasi Kondisi Pasien di ',
            fn(array $row) => $this->seedRs($row),
            'B2', 30
        );

        $this->importExcelSheet(
            'Identifikasi Kondisi Pasien d-1',
            fn(array $row) => $this->seedPuskesmas($row),
            'B2', 30
        );

        $this->seedUser();
    }

    private function importExcelSheet(string $sheetName, callable $callback, string $startCell, int $maxRows = 50): void
    {
        $reader = new \OpenSpout\Reader\XLSX\Reader();
        $reader->open(self::EXCEL_PATH);

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() !== $sheetName) {
                continue;
            }
            $this->processSheet($sheet, $callback, $startCell, $maxRows);
            break;
        }
        $reader->close();
    }

    private function processSheet($sheet, callable $callback, string $startCell, int $maxRows = 50): void
    {
        $col = ord(substr($startCell, 0, 1)) - ord('A');
        $rowOffset = (int)substr($startCell, 1) - 1;
        $rowCount = 0;

        foreach ($sheet->getRowIterator() as $rowIdx => $row) {
            if ($rowIdx <= $rowOffset) continue;
            if ($rowCount >= $maxRows) break;

            $cells = $row->cells;
            $rowData = [];
            foreach ($cells as $i => $cell) {
                $rowData[$i] = $this->cellValue($cell);
            }

            // Stop kalau kolom utama (kabupaten atau nama_rs) kosong / non-string
            // (artinya sudah masuk ke section "Logic" / "Limitasi" / dll).
            $callback($rowData);
            $rowCount++;
        }
    }

    private function cellValue(Cell $cell): mixed
    {
        $value = $cell->getValue();
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        if (is_string($value) && str_starts_with($value, '=')) {
            $reflection = new \ReflectionClass($cell);
            if ($reflection->hasMethod('getComputedValue')) {
                $method = $reflection->getMethod('getComputedValue');
                $method->setAccessible(true);
                try {
                    $computed = $method->invoke($cell);
                    if ($computed instanceof \DateTimeInterface) {
                        return $computed->format('Y-m-d');
                    }
                    if (is_numeric($computed)) {
                        $f = (float)$computed;
                        if ($f > 30000 && $f < 60000) {
                            $ts = ($f - 25569) * 86400;
                            return gmdate('Y-m-d', (int)$ts);
                        }
                        return (int)$f;
                    }
                    return $computed;
                } catch (\Throwable) {
                    return $value;
                }
            }
        }
        return $value;
    }

    private function seedAnalisa(array $row): void
    {
        [$tanggal, $namaKab, $korbanLuka, $pasienRs, $pasienPuskesmas, , , $polaGap, $status, $tindakLanjut] = array_pad($row, 10, null);
        if (empty($namaKab) || !$this->validKabupaten((string)$namaKab)) return;
        $kab = $this->findOrCreateKabupaten($namaKab);
        AnalisaRingkasan::updateOrCreate(
            ['kabupaten_id' => $kab->id, 'tanggal' => $this->parseDate($tanggal)],
            [
                'korban_luka' => (int)$korbanLuka,
                'pasien_rs' => (int)$pasienRs,
                'pasien_puskesmas' => (int)$pasienPuskesmas,
                'total_pasien' => (int)$pasienRs + (int)$pasienPuskesmas,
                'pola_gap' => $polaGap,
                'status' => $status,
                'tindak_lanjut' => $tindakLanjut,
            ]
        );
    }
    private function seedSituasi(array $row): void
    {
        // Layout sheet "Situasi Kesehatan dan Populasi ":
        // 0=Tgl, 1=TglData, 2=Waktu, 3=Provinsi, 4=Kabupaten, 5=Populasi,
        // 6=Meninggal, 7=LukaBerat, 8=LukaRingan, 9=Pengungsi, 10=Titik, 11=Sumber
        $namaKab = $row[4] ?? null;
        if (empty($namaKab) || !$this->validKabupaten((string)$namaKab)) return;
        $kab = $this->findOrCreateKabupaten($namaKab);
        if (!$kab) return;
        SituasiKesehatan::updateOrCreate(
            ['kabupaten_id' => $kab->id, 'tanggal' => $this->parseDate($row[0] ?? null)],
            [
                'waktu' => $row[2] ?? null,
                'populasi_terdampak' => (int)($row[5] ?? 0),
                'meninggal' => (int)($row[6] ?? 0),
                'luka_berat' => (int)($row[7] ?? 0),
                'luka_ringan' => (int)($row[8] ?? 0),
                'pengungsi' => (int)($row[9] ?? 0),
                'titik_pengungsian' => (int)($row[10] ?? 0),
                'sumber_data' => $row[11] ?? null,
            ]
        );
    }

    private function seedRs(array $row): void
    {
        // Layout: 0=Tgl, 4=Kabupaten, 5=NamaRS, 6=Merah, 7=Kuning, 8=Hijau, 9=Hitam, 11=Diagnosis, 12=Sumber
        $namaKab = $row[4] ?? null;
        $namaRs = $row[5] ?? null;
        if (empty($namaRs) || empty($namaKab) || !$this->validKabupaten((string)$namaKab)) return;
        $kab = $this->findOrCreateKabupaten($namaKab);
        if (!$kab) return;
        KondisiPasienRs::updateOrCreate(
            ['nama_rs' => $namaRs, 'tanggal' => $this->parseDate($row[0] ?? null)],
            [
                'kabupaten_id' => $kab->id,
                'merah' => (int)($row[6] ?? 0),
                'kuning' => (int)($row[7] ?? 0),
                'hijau' => (int)($row[8] ?? 0),
                'hitam' => (int)($row[9] ?? 0),
                'total_pasien' => (int)($row[10] ?? 0),
                'diagnosis' => $row[11] ?? null,
                'sumber_data' => $row[12] ?? null,
            ]
        );
    }

    private function seedPuskesmas(array $row): void
    {
        // Layout: 0=Tgl, 4=Kabupaten, 5=NamaPuskesmas, 6=Merah, 7=Kuning, 8=Hijau, 9=Hitam, 11=Diagnosis, 12=Sumber
        $namaKab = $row[4] ?? null;
        $namaPuskesmas = $row[5] ?? null;
        if (empty($namaPuskesmas) || empty($namaKab) || !$this->validKabupaten((string)$namaKab)) return;
        $kab = $this->findOrCreateKabupaten($namaKab);
        if (!$kab) return;
        KondisiPasienPuskesmas::updateOrCreate(
            ['nama_puskesmas' => $namaPuskesmas, 'tanggal' => $this->parseDate($row[0] ?? null)],
            [
                'kabupaten_id' => $kab->id,
                'merah' => (int)($row[6] ?? 0),
                'kuning' => (int)($row[7] ?? 0),
                'hijau' => (int)($row[8] ?? 0),
                'hitam' => (int)($row[9] ?? 0),
                'total_pasien' => (int)($row[10] ?? 0),
                'diagnosis' => $row[11] ?? null,
                'sumber_data' => $row[12] ?? null,
            ]
        );
    }

    private function findOrCreateKabupaten(string $nama): ?Kabupaten
    {
        $nama = trim(preg_replace('/\s+/', ' ', $nama));
        if (empty($nama)) return null;
        return Kabupaten::firstOrCreate(['nama_kabupaten' => $nama]);
    }

    private function parseDate(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        if (is_numeric($value)) {
            $f = (float)$value;
            if ($f > 30000 && $f < 60000) {
                $ts = ($f - 25569) * 86400;
                return gmdate('Y-m-d', (int)$ts);
            }
        }
        if (is_string($value)) {
            $ts = strtotime($value);
            if ($ts !== false) return gmdate('Y-m-d', $ts);
        }
        return '2026-08-18';
    }

    private function seedUser(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@sitrep-ntt.local',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
