<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUnitRequest;
use App\Http\Requests\Admin\UpdateUnitRequest;
use App\Repositories\UnitRepository;

class UnitController extends Controller
{
    private $unitRepository;
    public function __construct(UnitRepository $unitRepository)
    {
        $this->middleware('auth:admin');
        $this->unitRepository = $unitRepository;
    }

    public function store(StoreUnitRequest $request)
    {
        $unit = $this->unitRepository->store($request->validated());
        return response()->json("Item created successfully with id : $unit->id");
    }

    public function update(UpdateUnitRequest $request)
    {
        $unit = $this->unitRepository->update($request->validated());
        return response()->json("Item with id : $unit->id updated successfully");
    }

    public function delete($id)
    {
        $unit = $this->unitRepository->delete($id);
        return response()->json("Item with id : $unit->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->unitRepository->getAllUnits($text, request()->page_size, request()->status);
    }
    public function toggleStatus($id)
    {
        $unit = $this->unitRepository->toggleStatus($id);
        return response()->json("Status of item with id : $unit->id updated successfully");
    }
    public function find($id)
    {
        return $this->unitRepository->getUnit($id);
    }
}
