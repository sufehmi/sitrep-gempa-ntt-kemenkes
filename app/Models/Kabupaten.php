<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';
    protected $fillable = ['nama_kabupaten', 'latitude', 'longitude'];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    public function analisa(): HasMany
    {
        return $this->hasMany(AnalisaRingkasan::class, 'kabupaten_id');
    }

    public function situasi(): HasMany
    {
        return $this->hasMany(SituasiKesehatan::class, 'kabupaten_id');
    }

    public function rs(): HasMany
    {
        return $this->hasMany(KondisiPasienRs::class, 'kabupaten_id');
    }

    public function puskesmas(): HasMany
    {
        return $this->hasMany(KondisiPasienPuskesmas::class, 'kabupaten_id');
    }
}
