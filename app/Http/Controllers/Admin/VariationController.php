<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVariationRequest;
use App\Http\Requests\Admin\UpdateVariationRequest;
use App\Repositories\VariationRepository;

class VariationController extends Controller
{
    private $variationRepository;
    public function __construct(VariationRepository $variationRepository)
    {
        $this->middleware('auth:admin');
        $this->variationRepository = $variationRepository;
    }

    public function store(StoreVariationRequest $request)
    {
        $variation = $this->variationRepository->store($request->validated());
        return response()->json("Item created successfully with id : $variation->id");
    }

    public function update(UpdateVariationRequest $request)
    {
        $variation = $this->variationRepository->update($request->validated());
        return response()->json("Item with id : $variation->id updated successfully");
    }

    public function delete($id)
    {
        $variation = $this->variationRepository->delete($id);
        return response()->json("Item with id : $variation->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->variationRepository->getAllVariations($text, request()->page_size, request()->status);
    }
    public function toggleStatus($id)
    {
        $variation = $this->variationRepository->toggleStatus($id);
        return response()->json("Status of item with id : $variation->id updated successfully");
    }
    public function find($id)
    {
        return $this->variationRepository->getVariation($id);
    }
}
