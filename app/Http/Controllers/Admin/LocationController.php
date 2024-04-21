<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLocationRequest;
use App\Http\Requests\Admin\UpdateLocationRequest;
use App\Repositories\LocationRepository;

class LocationController extends Controller
{
    private $locationRepository;
    public function __construct(LocationRepository $locationRepository)
    {
        $this->middleware('auth:admin');
        $this->locationRepository = $locationRepository;
    }

    public function store(StoreLocationRequest $request)
    {
        $location = $this->locationRepository->store($request->validated());
        return response()->json("Item created successfully with id : $location->id");
    }

    public function update(UpdateLocationRequest $request)
    {
        $location = $this->locationRepository->update($request->validated());
        return response()->json("Item with id : $location->id updated successfully");
    }

    public function delete($id)
    {
        $location = $this->locationRepository->delete($id);
        return response()->json("Item with id : $location->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->locationRepository->getLocations($text, request()->page_size, request()->status);
    }
    public function togglePublish($id)
    {
        $location = $this->locationRepository->togglePublish($id);
        return response()->json("Publish of item with id : $location->id updated successfully");
    }
    public function toggleDefault($id)
    {
        $location = $this->locationRepository->toggleDefault($id);
        return response()->json("Defualt of item with id : $location->id updated successfully");
    }
    public function find($id)
    {
        return $this->locationRepository->getLocation($id);
    }
}
