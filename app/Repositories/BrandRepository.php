<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Brand;

class BrandRepository
{
    public function store($input)
    {
        return Brand::create($input);
    }
    public function update($input)
    {
        $brand = $this->find($input["id"]);
        $brand->update($input);
        return $brand;
    }
    public function delete($id)
    {
        $brand = $this->find($id);
        $brand->delete();
        return $brand;
        
    }
    public function getBrands($text, $page_size, $status)
    {
        if ($page_size) {
            return Brand::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->when($status !== null, function ($q) use ($status) {
                $q->where("status", $status);
            })
                ->with("mediaManager")
                ->orderBy("id", "desc")->paginate($page_size);
        }
        return Brand::get();
    }
    public function toggleStatus($id)
    {
        $brand = $this->find($id);
        $brand->status = $brand->status == 1 ? 0 : 1;
        $brand->save();
        return $brand;
    }
    public function getBrand($id)
    {
        return $this->find($id, ["mediaManager"]);
    }
    //Commons
    private function find($id, $relations = [])
    {
        $brand = Brand::with($relations)->find($id);
        if (!$brand) throw  new NotFoundException;
        return $brand;
    }
}
