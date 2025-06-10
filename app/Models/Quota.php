<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'fertilizer_id',
        'allocated_amount'=> 0,
        'used_amount',
        'notes'
    ];
    
    // Relasi dengan model lain
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class);
    }
    
    // Accessor untuk mendapatkan total alokasi berdasarkan luas lahan
    public function getAllocatedAmountAttribute($value)
    {
        // Jika user adalah petani dan memiliki land_area
        if ($this->user && $this->user->role === 'petani' && $this->user->land_area) {
            // Kuota per hektar berdasarkan jenis pupuk
            $quotaPerHectare = [
                'urea' => 200,
                'npk' => 150,
                'organik' => 500
            ];
            
            // Dapatkan nama pupuk
            $fertilizerName = strtolower($this->fertilizer->name);
            
            // Hitung berdasarkan luas lahan
            if (isset($quotaPerHectare[$fertilizerName])) {
                return $this->user->land_area * $quotaPerHectare[$fertilizerName];
            }
        }
        
        // Jika tidak cocok kondisi di atas, gunakan nilai dari database
        return $value;
    }
    
    // Accessor untuk mendapatkan sisa kuota
    public function getRemainingQuotaAttribute()
    {
        return $this->allocated_amount - $this->used_amount;
    }
}