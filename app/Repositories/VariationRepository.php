<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Variation;

class VariationRepository
{
    public function store($input)
    {
        return Variation::create($input);
    }

    public function update($input)
    {
        $variation = $this->find($input["id"]);
        $variation->update($input);
        return $variation;
    }

    public function delete($id)
    {
        $variation = $this->find($id);
        $variation->delete();
        return $variation;
    }
    public function getAllVariations($text, $page_size, $status)
    {
        if ($page_size) {
            return Variation::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->when($status !== null, function ($q) use ($status) {
                $q->where("status", $status);
            })
                ->orderBy("id", "desc")->paginate($page_size);
        }
        return Variation::get();
    }
    public function toggleStatus($id)
    {
        $variation = $this->find($id);
        $variation->status = $variation->status == 1 ? 0 : 1;
        $variation->save();
        return $variation;
    }
    public function getVariation($id)
    {
        return $this->find($id);
    }
    //Commons
    private function find($id)
    {
        $variation = Variation::find($id);
        if (!$variation) throw  new NotFoundException;
        return $variation;
    }
}
