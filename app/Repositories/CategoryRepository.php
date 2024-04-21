<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Brand;
use App\Models\Category;

class CategoryRepository
{
    public function store($input, $brand_ids)
    {
        $category = Category::create($input);
        $category->brands()->sync($brand_ids);
        return $category;
    }
    public function update($input, $brand_ids)
    {
        $category = $this->find($input["id"]);
        $category->update($input);
        $category->brands()->sync($brand_ids);
        return $category;
    }
    public function delete($id)
    {
        $category = $this->find($id);
        $category->delete();
        return $category;
    }

    public function getCategories($text, $page_size)
    {
        if ($page_size) {
            return Category::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })
                ->with(["brands", "baseCategory", "mediaManager"])
                ->orderBy("id", "desc")->paginate($page_size);
        }
        return Category::get();
    }
    public function toggleStatus($id)
    {
        $category = $this->find($id);
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
        return $category;
    }
    public function getCategory($id)
    {
        return $this->find($id, ["mediaManager", "brands", "baseCategory"]);
    }

    public function getBrands()
    {
        return Brand::where("status", 1)->get();
    }
    public function getChildCategory($baseCategoryId)
    {
        return Category::where("base_category_id", $baseCategoryId)->first();
    }
    public function find($id, $relations = [])
    {
        $category = Category::with($relations)->find($id);
        if (!$category) throw  new NotFoundException();
        return $category;
    }
    public function getBaseCategories($id, $childIds)
    {
        return Category::when($id, function ($q) use ($childIds, $id) {
            $q->where("id", "<>", $id)->whereNotIn("id", $childIds);
        })->get();
    }
}
