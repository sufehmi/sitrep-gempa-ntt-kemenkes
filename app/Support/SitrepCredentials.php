<?php

namespace App\Support;

use RuntimeException;

/**
 * Centralized credential reader for Sitrep NTT.
 *
 * Semua credential yang dipakai oleh runtime aplikasi (gate SHA1, default
 * seeder password, factory dummy) dibaca dari file password.ini di root
 * project. File ini SENGAJA di-gitignore sehingga tidak akan pernah muncul
 * di repository.
 *
 * Lokasi file dicek dari STORAGE_PATH atau BASE_PATH (Laravel default),
 * sehingga bekerja baik saat dijalankan via:
 *   - php artisan tinker / php artisan serve (BASE_PATH = repo root)
 *   - php artisan migrate --seed (BASE_PATH = repo root)
 *   - unit test (BASE_PATH = repo root)
 *   - crontab worker (BASE_PATH = repo root)
 *
 * Kalau file tidak ada ATAU key hilang, helper throw RuntimeException —
 * "fail closed" demi keamanan. Source code tidak mengandung credential
 * default sebagai fallback; helper ini SATU-SATUNYA sumber credential.
 *
 * Format password.ini (Laravel INI parser):
 *   [default_user]
 *   username = admin
 *   password = admin123
 *
 *   [sha1_gates]
 *   manage_user_sha1 = 8e4b4051c65d8e56b261860e5af16e4b2b8f74b8
 *   update_gate_sha1 = 8e4b4051c65d8e56b261860e5af16e4b2b8f74b8
 *
 *   [factory]
 *   default_password = password
 *
 * Untuk rotate credential: edit password.ini di server, tidak perlu deploy.
 */
final class SitrepCredentials
{
    private static ?array $cache = null;

    private const SECTIONS = [
        'default_user' => ['username', 'password'],
        'sha1_gates' => ['manage_user_sha1', 'update_gate_sha1'],
        'factory' => ['default_password'],
    ];

    /**
     * Path absolut ke file password.ini. Dicari di base_path() (= root repo).
     */
    public static function passwordIniPath(): string
    {
        // base_path() tersedia di runtime Laravel, tapi kalau dipanggil dari
        // luar framework (mis. CLI script yang bootstraps manual), fallback
        // ke dirname(__DIR__, 2) = root repo.
        if (function_exists('base_path')) {
            return base_path('password.ini');
        }
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'password.ini';
    }

    /**
     * Baca + cache seluruh isi password.ini. Throw kalau file tidak ada.
     */
    private static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        $path = self::passwordIniPath();
        if (!is_readable($path)) {
            throw new RuntimeException(
                "SitrepCredentials: password.ini tidak ditemukan / tidak terbaca di {$path}. "
                . "File WAJIB ada di server. Lihat README untuk format."
            );
        }
        $parsed = @parse_ini_file($path, true, INI_SCANNER_RAW);
        if ($parsed === false) {
            throw new RuntimeException(
                "SitrepCredentials: password.ini gagal di-parse. Cek syntax INI-nya."
            );
        }
        // Validasi: setiap section + key yang dikenal WAJIB ada. Kalau satu
        // hilang, fail closed supaya tidak ada default diam-diam.
        foreach (self::SECTIONS as $section => $keys) {
            if (!isset($parsed[$section])) {
                throw new RuntimeException(
                    "SitrepCredentials: section [{$section}] tidak ada di password.ini."
                );
            }
            foreach ($keys as $key) {
                if (!isset($parsed[$section][$key]) || $parsed[$section][$key] === '') {
                    throw new RuntimeException(
                        "SitrepCredentials: key {$section}.{$key} tidak ada / kosong di password.ini."
                    );
                }
            }
        }
        self::$cache = $parsed;
        return self::$cache;
    }

    /**
     * Username untuk default seeder user (biasanya 'admin').
     */
    public static function defaultUsername(): string
    {
        return self::all()['default_user']['username'];
    }

    /**
     * Plaintext password untuk default seeder user (admin). Disimpan
     * sebagai Hash::make() sebelum ditulis ke DB.
     */
    public static function defaultUserPassword(): string
    {
        return self::all()['default_user']['password'];
    }

    /**
     * SHA1 hash untuk Manage User gate (/users). Hard-compare via hash_equals().
     */
    public static function manageUserSha1(): string
    {
        return self::all()['sha1_gates']['manage_user_sha1'];
    }

    /**
     * SHA1 hash untuk /update gate (edit/hapus data).
     */
    public static function updateGateSha1(): string
    {
        return self::all()['sha1_gates']['update_gate_sha1'];
    }

    /**
     * Plaintext default password untuk UserFactory (dummy/testing).
     */
    public static function factoryDefaultPassword(): string
    {
        return self::all()['factory']['default_password'];
    }

    /**
     * Reset cache (untuk testing). Tidak dipakai di runtime normal.
     */
    public static function flush(): void
    {
        self::$cache = null;
    }
}