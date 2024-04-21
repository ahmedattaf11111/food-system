<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTaxRequest;
use App\Http\Requests\Admin\UpdateTaxRequest;
use App\Models\Tax;
use App\Repositories\TaxRepository;
use Illuminate\Support\Facades\Storage;

class TaxController extends Controller
{
    private $taxRepository;
    public function __construct(TaxRepository $taxRepository)
    {
        $this->middleware('auth:admin');
        $this->taxRepository = $taxRepository;
    }

    public function store(StoreTaxRequest $request)
    {
        $tax = $this->taxRepository->store($request->validated());
        return response()->json("Item created successfully with id : $tax->id");
    }

    public function update(UpdateTaxRequest $request)
    {
        $tax = $this->taxRepository->update($request->validated());
        return response()->json("Item with id : $tax->id updated successfully");
    }

    public function delete($id)
    {
        $tax = $this->taxRepository->delete($id);
        return response()->json("Item with id : $tax->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->taxRepository->getAllTaxes($text, request()->page_size, request()->status);
    }

    public function toggleStatus($id)
    {
        $tax = $this->taxRepository->toggleStatus($id);
        return response()->json("Status of item with id : $tax->id updated successfully");
    }
    public function find($id)
    {
        return $this->taxRepository->getTax($id);
    }
}
