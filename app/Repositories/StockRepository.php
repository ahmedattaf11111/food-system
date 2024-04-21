<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Location;
use App\Models\Product;
use App\Models\Stock;

class StockRepository
{
    public function getLocations()
    {
        return Location::where("published", 1)->get();
    }
    public function getProducts()
    {
        return Product::where("published", 1)->get();
    }
    public function getStocks($locationId, $productId)
    {
        $product = $this->find(Product::class, $productId);
        $variations = $this->getStockVariations($locationId, $productId);
        if (!$product->has_variations) {
            return [["stock" => $variations->count() > 0 ? $variations[0]->stock : 0]];
        }
        return $variations->map(function ($variation) {
            return [
                "variation_name_ar" => $variation->variation_name_ar,
                "variation_name_en" => $variation->variation_name_en,
                "stock" => $variation->stock,
            ];
        });
    }
    public function insertStock($input)
    {
        foreach ($input["stocks"] as $stock) {
            $item = $this->getStock($stock, true, true);
            if ($item) {
                $item->update($stock);
            } else {
                //get price and code sku and insert new stock
                $item = $this->getStock($stock, false, false);
                $stock["price"] = $item->price;
                $stock["sku"] = $item->sku;
                $stock["code"] = $item->code;
                Stock::insert($stock);
            }
        }
    }
    private function getStock($stock, $withLocation = true, $checkVariationName = true)
    {
        return Stock::when(
            $withLocation,
            function ($q) use ($stock) {
                $q->where("location_id", $stock["location_id"]);
            },
            function ($q) {
                $q->whereNull("location_id");
            }
        )
            ->where("product_id", $stock["product_id"])
            ->when($checkVariationName && $stock["variation_name_ar"] && $stock["variation_name_en"], function ($q) use ($stock) {
                //if product has variations
                $q->where("variation_name_ar", $stock["variation_name_ar"])
                    ->where("variation_name_en", $stock["variation_name_en"]);
            })->first();
    }
    //Commons
    private function getStockVariations($locationId, $productId)
    {
        $variations = Stock::where("location_id", $locationId)->where("product_id", $productId)->get();
        if ($variations->count() == 0) {
            $variations = Stock::whereNull("location_id")->where("product_id", $productId)->get();
        }
        return $variations;
    }
    private function find($modelClass, $id)
    {
        $model = $modelClass::find($id);
        if (!$model) throw  new NotFoundException;
        return $model;
    }
}
