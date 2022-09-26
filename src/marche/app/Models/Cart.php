<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    public function scopeUserId($query, $id) {
        return $query->where('user_id', $id);
    }

    public function scopeProductId($query, $id) {
        return $query->where('user_id', $id);
    }
}
