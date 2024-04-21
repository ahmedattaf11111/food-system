<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        "variation_values_ids" => "json",
    ];
    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
