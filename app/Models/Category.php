<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ["brand_ids"];

    public function brands()
    {
        return $this->belongsToMany(Brand::class, CategoryBrand::class, "category_id", "brand_id");
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, ProductCategory::class, "category_id", "product_id");
    }
    public function mediaManager()
    {
        return $this->belongsTo(MediaManager::class);
    }
    public function baseCategory()
    {
        return $this->belongsTo(Category::class);
    }
}
