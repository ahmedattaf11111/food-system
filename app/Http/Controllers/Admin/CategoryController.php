<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->middleware('auth:admin');
        $this->categoryRepository = $categoryRepository;
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryRepository->store($request->validated(), $request->brand_ids);
    }

    public function update(UpdateCategoryRequest $request)
    {
        $this->categoryRepository->update($request->validated(), $request->brand_ids);
    }

    public function delete($id)
    {
        $this->categoryRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->categoryRepository->getCategories($text, request()->page_size);
    }

    public function toggleStatus($id)
    {
        $this->categoryRepository->toggleStatus($id);
    }

    public function find($id)
    {
        return $this->categoryRepository->getCategory($id);
    }
    public function getBaseCategories()
    {
        $childIds = [];
        if (request()->id) {
            $currentCategory = $this->categoryRepository->find(request()->id);
            $this->setChildIds($currentCategory, $childIds);
        }
        $categories = $this->categoryRepository->getBaseCategories(request()->id, $childIds);
        foreach ($categories as $category) {
            $category->parent_counts = $this->getParentsCountToCategory($category, 0);
        }
        return $categories;
    }
    public function getBrands()
    {
        return $this->categoryRepository->getBrands();
    }
    //Commons
    private function getParentsCountToCategory($category, $parentCount = 0)
    {
        if ($cat = $category->baseCategory) {
            $parentCount++;
            return $this->getParentsCountToCategory($cat, $parentCount);
        } else {
            return $parentCount;
        }
    }
    private function setChildIds($category, &$childIds)
    {

        $cat = $this->categoryRepository->getChildCategory($category->id);
        if ($cat) {
            $childIds[] = $cat->id;
            $this->setChildIds($cat, $childIds);
        } else {
            return;
        }
    }
}
