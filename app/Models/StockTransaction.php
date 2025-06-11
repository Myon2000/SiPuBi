<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'fertilizer_id',
        'quantity',
        'type', // in, out
        'description',
        'performed_by',
    ];

    // Relasi dengan Fertilizer
    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class);
    }

    // Relasi dengan User yang melakukan
    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    protected function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value ?? 'Transaksi stok pupuk';
    }
}