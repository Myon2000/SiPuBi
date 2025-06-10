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
        'stock',
        'minimum_stock',
        'status'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function quotas()
    {
        return $this->hasMany(Quota::class);
    }
}