<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shop extends Model {
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling'
    ];

    public function owner() {
        return $this->belongsTo(Owner::class);
    }

    public function product() {
        return $this->hasMany(Product::class);
    }

    public function scopeOwnerId($query, $id) {
        return $query->where('owner_id', $id);
    }
}
