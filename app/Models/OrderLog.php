<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    protected $casts = [
        'created_at'  => 'date:d M, Y',
        'updated_at'  => 'date:d M, Y',
    ];
}
