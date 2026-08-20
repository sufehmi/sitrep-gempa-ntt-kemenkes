<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = ['name', 'key', 'prefix', 'created_by', 'is_active'];

    protected $casts = [
        'last_used_at' => 'datetime',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
    ];

    protected $hidden = ['key'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate a new key with given name. Returns the model and the plaintext key
     * (the plaintext is NOT stored — only its SHA-256 hash + a short prefix for lookup).
     */
    public static function generate(string $name, ?int $createdBy = null): array
    {
        $plain = 'ntt_' . Str::random(40);
        $hash = hash('sha256', $plain);
        $prefix = substr($plain, 0, 8);

        $key = self::create([
            'name' => $name,
            'key' => $hash,
            'prefix' => $prefix,
            'created_by' => $createdBy,
            'is_active' => true,
        ]);

        return ['model' => $key, 'plain' => $plain];
    }

    public static function findByPlaintext(string $plain): ?self
    {
        return self::where('key', hash('sha256', $plain))->where('is_active', true)->first();
    }
}
