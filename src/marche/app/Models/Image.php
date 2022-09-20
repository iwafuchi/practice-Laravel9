<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Image extends Model {
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'filename',
        'title',
    ];

    public function Owner() {
        return $this->belongsTo(Owner::class);
    }

    public function scopeOwnerId($query, $id) {
        return $query->where('owner_id', $id);
    }
}
