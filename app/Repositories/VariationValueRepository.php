<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\VariationValue;

class VariationValueRepository
{
    public function store($input)
    {
        return VariationValue::create($input);
    }
    public function update($input)
    {
        $variationValue = $this->find($input["id"]);
        $variationValue->update($input);
        return $variationValue;
    }

    public function delete($id)
    {
        $variationValue = $this->find($id);
        $variationValue->delete();
        return $variationValue;
    }

    public function getAllVariationValues($variation_id, $text, $page_size, $status)
    {
        if ($page_size) {
            return VariationValue::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->when($status !== null, function ($q) use ($status) {
                $q->where("status", $status);
            })
                ->where("variation_id", $variation_id)
                ->orderBy("id", "desc")->paginate($page_size);
        }
        return VariationValue::get();
    }
    public function toggleStatus($id)
    {
        $variationValue = $this->find($id);
        $variationValue->status = $variationValue->status == 1 ? 0 : 1;
        $variationValue->save();
        return $variationValue;
    }
    public function getVariationValue($id)
    {
        return $this->find($id);
    }
    //Commons
    private function find($id)
    {
        $variationValue = VariationValue::find($id);
        if (!$variationValue) throw  new NotFoundException;
        return $variationValue;
    }
}
