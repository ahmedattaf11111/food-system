<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ["order_details"];
    protected $casts = [
        "created_at" => "date:d M, Y"
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
