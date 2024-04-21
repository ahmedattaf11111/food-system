<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function mediaManager()
    {
        return $this->belongsTo(MediaManager::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
