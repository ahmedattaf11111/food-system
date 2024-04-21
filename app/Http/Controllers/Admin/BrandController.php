<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{
    private $brandRepository;
    public function __construct(BrandRepository $brandRepository)
    {
        $this->middleware('auth:admin');
        $this->brandRepository = $brandRepository;
    }

    public function store(StoreBrandRequest $request)
    {
        $brand = $this->brandRepository->store($request->validated());
        return response()->json("Item created successfully with id : $brand->id");
    }

    public function update(UpdateBrandRequest $request)
    {
        $brand = $this->brandRepository->update($request->validated());
        return response()->json("Item with id : $brand->id updated successfully");
    }

    public function delete($id)
    {
        $brand = $this->brandRepository->delete($id);
        return response()->json("Item with id : $brand->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->brandRepository->getBrands($text, request()->page_size, request()->status);
    }
    public function toggleStatus($id)
    {
        $brand = $this->brandRepository->toggleStatus($id);
        return response()->json("Status of item with id : $brand->id updated successfully");
    }
    public function find($id)
    {
        return $this->brandRepository->getBrand($id);
    }
}
