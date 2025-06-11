<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fertilizer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'current_stock',
        'minimum_stock',
        'status'
    ];

    public const TYPES = [
        'urea' => 'Pupuk Urea',
        'npk' => 'Pupuk NPK',
        'organik' => 'Pupuk Organik'
    ];

    public const QUOTA_PER_HECTARE = [
        'urea' => 200, // kg per hektar
        'npk' => 150,  // kg per hektar
        'organik' => 500 // kg per hektar
    ];

    // Metode untuk mendapatkan kuota per hektar berdasarkan jenis pupuk
    public function getQuotaPerHectare()
    {
        $key = strtolower($this->name);
        return self::QUOTA_PER_HECTARE[$key] ?? 0;
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function quotas()
    {
        return $this->hasMany(Quota::class);
    }
}