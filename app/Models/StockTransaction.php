<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fertilizer_id',
        'quantity',
        'transaction_type', // 'in' atau 'out'
        'notes',
        'admin_id'
    ];

    protected $casts = [
        'transaction_date' => 'datetime'
    ];

    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}