<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVariationValueRequest;
use App\Http\Requests\Admin\UpdateVariationValueRequest;
use App\Repositories\VariationValueRepository;

class VariationValueController extends Controller
{
    private $variationValueRepository;
    public function __construct(VariationValueRepository $variationValueRepository)
    {
        $this->middleware('auth:admin');
        $this->variationValueRepository = $variationValueRepository;
    }


    public function store(StoreVariationValueRequest $request)
    {
        $variationValue = $this->variationValueRepository->store($request->validated());
        return response()->json("Item created successfully with id : $variationValue->id");
    }

    public function update(UpdateVariationValueRequest $request)
    {
        $variationValue = $this->variationValueRepository->update($request->validated());
        return response()->json("Item with id : $variationValue->id updated successfully");
    }

    public function delete($id)
    {
        $variationValue = $this->variationValueRepository->delete($id);
        return response()->json("Item with id : $variationValue->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->variationValueRepository->getAllVariationValues(
            request()->variation_id,
            $text,
            request()->page_size,
            request()->status
        );
    }
    public function toggleStatus($id)
    {
        $variationValue = $this->variationValueRepository->toggleStatus($id);
        return response()->json("Status of item with id : $variationValue->id updated successfully");
    }
    public function find($id)
    {
        return $this->variationValueRepository->getVariationValue($id);
    }
}
