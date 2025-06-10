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
        'allocated_amount',
        'used_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class);
    }

    public function remainingQuota()
    {
        return $this->allocated_amount - $this->used_amount;
    }
}