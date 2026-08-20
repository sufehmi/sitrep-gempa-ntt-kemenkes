<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KondisiPasienRs extends Model
{
    protected $table = 'kondisi_pasien_rs';
    protected $fillable = [
        'kabupaten_id', 'nama_rs', 'tanggal', 'merah', 'kuning',
        'hijau', 'hitam', 'total_pasien', 'diagnosis', 'sumber_data',
    ];
    protected $casts = ['tanggal' => 'date'];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
}
