<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'role',
        'is_active',
        'created_at',
        'updated_at',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'user_id');
    }
     
    public function approvedReports()
    {
        return $this->hasMany(LaporanHarian::class, 'approved_by');
    }
    
    public function reports()
    {
        return $this->hasMany(LaporanHarian::class,'approved_by');
        
    }

    public function RejectedReports()
    {
        return $this->hasMany(LaporanHarian::class,'rejected_by');
    }

    public function operatorSiangReports()
    {
        return $this->hasMany(LaporanHarian::class,'operator_siang_id');
    }

    public function operatorMalamReports()
    {
        return $this->hasMany(LaporanHarian::class,'operator_malam_id');
    }


}