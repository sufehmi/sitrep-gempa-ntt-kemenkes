<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KondisiPasienPuskesmas extends Model
{
    protected $table = 'kondisi_pasien_puskesmas';
    protected $fillable = [
        'kabupaten_id', 'nama_puskesmas', 'tanggal', 'merah', 'kuning',
        'hijau', 'hitam', 'total_pasien', 'diagnosis', 'sumber_data',
    ];
    protected $casts = ['tanggal' => 'date'];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
}
