<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use App\Models\Tag;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Variation;
use App\Models\VariationValue;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function store($input)
    {
        return DB::transaction(function () use ($input) {
            $product = Product::create($input);
            if (isset($input["media_manager_ids"])) $product->gallaries()->sync($input["media_manager_ids"]);
            if (isset($input["categories_ids"])) $product->categories()->sync($input["categories_ids"]);
            if (isset($input["tags_ids"])) $product->tags()->sync($input["tags_ids"]);
            if (isset($input["taxes"]))  $product->taxes()->sync($this->getSyncFormat($input["taxes"]));
            if (isset($input["variations"]))
                $this->syncVariations($this->mapVariations($product->id, $input["variations"]));
            if (isset($input["variation_ids"]))
                $this->syncVariationIds($this->mapVariationIds($product->id, $input["variation_ids"]));
            return $product;
        });
    }
    public function update($input)
    {
        $product = $this->find($input["id"]);
        return DB::transaction(function () use ($input, $product) {
            $product->update($input);
            if (isset($input["media_manager_ids"])) $product->gallaries()->sync($input["media_manager_ids"]);
            if (isset($input["categories_ids"])) $product->categories()->sync($input["categories_ids"]);
            if (isset($input["tags_ids"])) $product->tags()->sync($input["tags_ids"]);
            if (isset($input["taxes"]))  $product->taxes()->sync($this->getSyncFormat($input["taxes"]));
            if (isset($input["variations"]))
                $this->syncVariations($this->mapVariations($product->id, $input["variations"]));
            if (isset($input["variation_ids"]))
                $this->syncVariationIds($this->mapVariationIds($product->id, $input["variation_ids"]));
            return $product;
        });
    }
    public function delete($id)
    {
        $product = $this->find($id);
        $product->delete();
        return $product;
    }
    public function getAllProducts($text, $page_size, $brand_id, $published)
    {
        if ($page_size) {
            $products = Product::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->when($published !== null, function ($q) use ($published) {
                $q->where("published", $published);
            })
                ->when($brand_id !== null, function ($q) use ($brand_id) {
                    $q->where("brand_id", $brand_id);
                })
                ->with(["variations" => function ($q) {
                    $q->whereNull("location_id");
                }])
                ->with(["mediaManager", "brand", "categories"])
                ->orderBy("id", "desc")->paginate($page_size);
            $products->transform(function ($product) {
                $product->max_price = $product->variations->max(function ($variation) use ($product) {
                    return $variation->price;
                });
                $product->min_price = $product->variations->min(function ($variation) use ($product) {
                    return $variation->price;
                });
            
                $product->setHidden(["variations"]);
                return $product;
            });

            return $products;
        }
        return Product::get();
    }
    public function togglePublish($id)
    {
        $product = $this->find($id);
        $product->published = $product->published == 1 ? 0 : 1;
        $product->save();
        return $product;
    }
    public function getProduct($id)
    {
        $location = Location::where("default", 1)->first();
        $relations = ["mediaManager", "gallaries", "categories", "tags", "taxes", "variationIds"];
        if ($location) {
            $relations["variations"] = function ($q) use ($location) {
                $q->where("location_id", $location->id);
            };
        }
        return $this->find($id, $relations);
    }
    //Drop down list
    public function getCategories()
    {
        return Category::get();
    }
    public function getBrands()
    {
        return Brand::get();
    }
    public function getUnits()
    {
        return Unit::get();
    }
    public function getTags()
    {
        return Tag::get();
    }

    public function getVariations()
    {
        return Variation::with("variationValues")->get();
    }
    public function getVariationValues($variationId)
    {
        return VariationValue::where("variation_id", $variationId)->get();
    }

    public function getTaxes()
    {
        return Tax::get();
    }

    //Commons
    private function syncVariations($variations)
    {
        Stock::where("product_id", $variations[0]["product_id"])->delete();
        //Insert variation with no location
        Stock::insert(collect($variations)->map(function ($variation) {
            $variation["location_id"] = null;
            $variation["stock"] = 0;
            $variation["price"] = $variation["price"];
            $variation["code"] = $variation["code"];
            $variation["sku"] = $variation["sku"];
            return $variation;
        })->toArray());
        //Insert variation with defualt location
        if ($variations[0]["location_id"]) {
            Stock::insert($variations);
        }
    }
    private function syncVariationIds($variationIds)
    {
        ProductVariation::where("product_id", $variationIds[0]["product_id"])->delete();
        ProductVariation::insert($variationIds);
    }

    private function mapVariations($productId, $variations)
    {
        $location = Location::where("default", 1)->first();
        return  collect($variations)->map(function ($variation) use ($location, $productId) {
            $variation["location_id"] = $location ? $location->id : null;
            $variation["product_id"] = $productId;
            return $variation;
        })->toArray();
        return $variations;
    }
    private function mapVariationIds($productId, $variationIds)
    {
        return collect($variationIds)->map(function ($variationId) use ($productId) {
            $variationId["product_id"] = $productId;
            $variationId["variation_values_ids"] = json_encode($variationId["variation_values_ids"]);
            return $variationId;
        })->toArray();
    }
    private function getSyncFormat($taxes)
    {
        $_taxes = [];
        foreach ($taxes as $tax) {
            $_taxes[$tax["tax_id"]] =  ["value" => $tax["value"], "percent" => $tax["percent"]];
        }
        return $_taxes;
    }
    private function find($id, $relations = [])
    {
        $product = Product::with($relations)->find($id);
        if (!$product) throw  new NotFoundException;
        return $product;
    }
}
