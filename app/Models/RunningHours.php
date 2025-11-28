<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunningHours extends Model
{
    use HasFactory;
    protected $table = 'running_hours';

    protected $fillable = [
        'laporan_id',
        'dari_jam',
        'sd_jam',
        'jumlah_jam',
        'keterangan',
        'downtime_jam'
    ];

    protected  $casts = [
        'dari_jam' => 'datetime',
        'sd_jam' => 'datetime',
        'jumlah_jam' => 'float',
        'downtime_jam' => 'float'
    ];


    public function laporan( ){
        return $this->belongsTo(LaporanHarian::class,'laporan_id');
    }

}