<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
