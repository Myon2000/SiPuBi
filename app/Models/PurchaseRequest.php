<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farmer_id',
        'fertilizer_id',
        'quantity',
        'total_price', // Tambahkan field ini
        'status', // pending, approved, rejected
        'notes',
        'processed_by', // Tambahkan field ini juga jika digunakan
        'processed_date', // Tambahkan field ini juga jika digunakan
    ];

    protected $casts = [
        'processed_date' => 'datetime', // Tambahkan cast untuk field ini jika digunakan
    ];

    // Relasi dengan User (Petani)
    public function farmer() // Ubah nama method ini dari user() menjadi farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    // Relasi dengan Fertilizer
    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class);
    }

    // Relasi dengan Admin yang menyetujui
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    
    // Helper methods untuk cek status
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}