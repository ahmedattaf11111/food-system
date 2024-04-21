<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockRequest;
use App\Repositories\StockRepository;

class StockController extends Controller
{
    private $stockRepository;
    public function __construct(StockRepository $stockRepository)
    {
        $this->middleware('auth:admin');
        $this->stockRepository = $stockRepository;
    }
    public function getProducts()
    {
        return $this->stockRepository->getProducts();
    }
    public function getLocations()
    {
        return $this->stockRepository->getLocations();
    }
    public function getStocks()
    {
        return $this->stockRepository->getStocks(
            request()->location_id,
            request()->product_id
        );
    }
    public function insertStock(StockRequest $request)
    {
       return  $this->stockRepository->insertStock($request->validated());
        // return response()->json("Stock updated successfully");
    }
}
