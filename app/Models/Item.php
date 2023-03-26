<?php

namespace App\Models;

use App\Commons\Traits\Image;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use Image;
    protected $guarded = [];
    protected $casts = [
        'list' => 'json',
    ];
}
