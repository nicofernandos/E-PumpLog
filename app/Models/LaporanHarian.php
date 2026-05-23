<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory;

    protected $table = 'laporan_harian';

    protected $fillable = [
        'user_id',
        'lokasisp_id',
        'pompa_id',
        'tanggal',
        'injeksi_ke',
        'facility',
        'total_cumulative',
        'operator_siang_id',
        'operator_malam_id',
        'keterangan',
        'status_code',
        'status',
        'approved_by',
        'approved_at',
        'is_deleted',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'status_code'      => 'integer',
        'is_deleted'       => 'boolean',
        'approved_at'      => 'integer', // bigint di DB, gunakan Carbon::createFromTimestamp()
        'total_cumulative' => 'float',
    ];

    // ── Relasi ────────────────────────────────────────────────

    public function user()
    {
        // Fix Bug #1: foreign key adalah 'user_id', bukan 'id'
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasisp_id');
    }

    public function pompa()
    {
        return $this->belongsTo(Pompa::class, 'pompa_id');
    }

    public function operatorSiang()
    {
        return $this->belongsTo(User::class, 'operator_siang_id');
    }

    public function operatorMalam()
    {
        return $this->belongsTo(User::class, 'operator_malam_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function detilJam()
    {
        return $this->hasMany(LaporanDetilJam::class, 'laporan_id');
    }

    public function runningHours()
    {
        return $this->hasMany(RunningHours::class, 'laporan_id');
    }

    // ── Helper status ─────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'finalized' => 'Finalized',
            'verified'  => 'Verified',
            'approved'  => 'Approved',
            default     => ucfirst($this->status ?? '-'),
        };
    }

    public function getApprovedAtFormattedAttribute(): ?string
    {
        if (!$this->approved_at) return null;
        return \Carbon\Carbon::createFromTimestamp($this->approved_at)
                             ->format('d/m/Y H:i');
    }
}