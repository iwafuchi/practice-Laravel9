<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
    ];

    protected $table = 't_stocks';

    public function scopeProductId($query, $id) {
        return $query->where('product_id', $id);
    }
}
