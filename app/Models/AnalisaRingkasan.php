<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalisaRingkasan extends Model
{
    use SoftDeletes;

    protected $table = 'analisa_ringkasan';
    protected $fillable = [
        'kabupaten_id', 'tanggal', 'korban_luka', 'pasien_rs',
        'pasien_puskesmas', 'total_pasien', 'pola_gap', 'status', 'tindak_lanjut',
    ];
    protected $casts = ['tanggal' => 'date'];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
}
