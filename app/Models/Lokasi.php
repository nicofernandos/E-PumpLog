<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasisp';

    // Sesuaikan dengan nama kolom di database (create_at, update_at)
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    protected $fillable = [
        'kodesp',
        'namasp',
        'keterangan',
        'is_deleted',
    ];

    protected $casts = [
        'create_at' => 'datetime',
        'update_at' => 'datetime',
    ];

    public function pompas(){
        return $this->hasMany(Pompa::class, 'lokasi_id', 'id');
    }

}