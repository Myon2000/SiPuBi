<?php
// filepath: d:\Kuliah\SEMESTER 4\PWEB\Project\SiPuBi\app\Models\User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'address',
        'phone',
        'status',
        'land_area',
        'ktp_image',
        'land_area_verified',
        'latitude',
        'longitude',
        'verification_requested_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
        'land_area_verified' => 'boolean',
        'verification_requested_at' => 'datetime',
    ];

    protected $attributes = [
        'role' => 'petani',
        'status' => true
    ];

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'farmer_id');
    }

    public function quotas()
    {
        return $this->hasMany(Quota::class);
    }

    public function approvedRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'processed_by');
    }

    public function verificationRequests()
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function hasPendingVerification()
    {
        return $this->verificationRequests()
            ->where('status', 'pending')
            ->exists();
    }

    public function getFormattedAddressAttribute()
    {
        return $this->address ?: 'Alamat belum diisi';
    }

    public function getFormattedLandAreaAttribute()
    {
        return $this->land_area ? number_format($this->land_area, 2) . ' hektar' : '-';
    }
}