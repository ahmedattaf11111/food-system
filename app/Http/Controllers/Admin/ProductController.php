<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        // $this->middleware('auth:admin');
        $this->productRepository = $productRepository;
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productRepository->store($request->validated());
        return response()->json("Item created successfully with id : $product->id");
    }

    public function update(UpdateProductRequest $request)
    {
        $product = $this->productRepository->update($request->validated());
        return response()->json("Item with id : $product->id updated successfully");
    }

    public function delete($id)
    {
        $product = $this->productRepository->delete($id);
        return response()->json("Item with id : $product->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->productRepository->getAllProducts($text, request()->page_size, request()->brand_id, request()->published);
    }

    public function togglePublish($id)
    {
        $product = $this->productRepository->togglePublish($id);
        return response()->json("Status of item with id : $product->id updated successfully");
    }

    public function find($id)
    {
        return $this->productRepository->getProduct($id);
    }
    //Dropdonw list
    public function getCategories()
    {
        return $this->productRepository->getCategories();
    }
    public function getBrands()
    {
        return $this->productRepository->getBrands();
    }
    public function getUnits()
    {
        return $this->productRepository->getUnits();
    }
    public function getTags()
    {
        return $this->productRepository->getTags();
    }
    
    public function getVariations()
    {
        return $this->productRepository->getVariations();
    }
    public function getTaxes()
    {
        return $this->productRepository->getTaxes();
    }
    public function getVariationValues($variationId)
    {
        return $this->productRepository->getVariationValues($variationId);
    }
    
}
