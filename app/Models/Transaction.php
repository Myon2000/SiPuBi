<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fertilizer_id',
        'quantity',
        'total_price',
        'status',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class);
    }
}