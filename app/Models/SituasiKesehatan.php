<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SituasiKesehatan extends Model
{
    protected $table = 'situasi_kesehatan';
    protected $fillable = [
        'kabupaten_id', 'tanggal', 'waktu', 'populasi_terdampak',
        'meninggal', 'luka_berat', 'luka_ringan', 'pengungsi',
        'titik_pengungsian', 'sumber_data', 'catatan',
    ];
    protected $casts = ['tanggal' => 'date'];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
}
