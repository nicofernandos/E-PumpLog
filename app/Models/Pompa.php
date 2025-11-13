<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pompa extends Model
{
    use HasFactory;
    protected $table = 'pompa';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'kodepompa',
        'jenispompa',
        'kapasitas',
        'lokasi_id',
        'status',
    ];

    public function lokasi(){
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
    

}