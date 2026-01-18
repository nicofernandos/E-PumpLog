<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanDetilJam extends Model
{
    use HasFactory;
    protected $table = 'laporan_detil_jam';

    protected $fillable = [
        'laporan_id',
        'jam_ke',
        'total_bbls',
        'rate_jam',
        'cumm_bbls',
        'rate_hari',
        'inj_psi',
        'inj_rpm',
        'oli_cf',
        'press_cf',
        'water_cf',
        'freq_hz',
        'is_deleted'
    ];

    protected $casts = [
        'jam_ke' => 'integer',
        'total_bbls' => 'float',
        'rate_jam' => 'float',
        'cumm_bbls' => 'float',
        'rate_hari' => 'float',
        'inj_psi' => 'float',
        'inj_rpm' => 'float',
        'oli_cf' => 'float',
        'press_cf' => 'float',
        'water_cf' => 'float',
        'freq_hz' => 'float',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanHarian::class,'laporan_id');
    }
}