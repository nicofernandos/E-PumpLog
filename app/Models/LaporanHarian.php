<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;
use PgSql\Lob;

class LaporanHarian extends Model
{
    use HasFactory;
    protected $table = 'laporan_harian';

    protected $fillable = [
        'user_id',
        'lokasisp_id'
        'pompa_id',
        'tanggal',
        'injeksi_ke',
        'facility',
        'total_cumulative',
        'operation_siang_id',
        'operation_malam_id',
        'keterangan',
        'status'
    ];

    $protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function lokasi(){
        return $this->belongsTo(Lokasi::class, 'lokasisp_id');
    }

    public function pompa(){
        return $this->belongsTo(Pompa::class);
    }

    public function operatorSiang(){ 
        return $this->belongsTo(User::class,'operator_siang_id');
    }

    public function operatorMalam(){ 
        return $this->belongsTo(User::class,'operator_malam_id');
    }

    public function detilJam(){
        return $this->hasMany(LaporanDetilJam::class,'Laporan_id');
    }

    public function runningHours(){
        return $this->hasMany(runningHours::class,'laporan_id');
    }
    
}