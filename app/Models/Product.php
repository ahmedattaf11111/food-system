<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ["media_manager_ids", "categories_ids", "tags_ids", "taxes", "variations"];
    use HasFactory;
    public function gallaries()
    {
        return $this->belongsToMany(MediaManager::class, ProductGallary::class, "product_id", "media_manager_id");
    }
    public function mediaManager()
    {
        return $this->belongsTo(MediaManager::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, ProductCategory::class, "product_id", "category_id");
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, ProductTag::class, "product_id", "tag_id");
    }
    public function taxes()
    {
        return $this->belongsToMany(Tax::class, ProductTax::class, "product_id", "tax_id")
            ->withPivot('value', 'percent');
    }
    public function variations()
    {
        return $this->hasMany(Stock::class);
    }
    public function locationVariations()
    {
        return $this->hasMany(Stock::class);    
    }
    
    public function variationIds()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
