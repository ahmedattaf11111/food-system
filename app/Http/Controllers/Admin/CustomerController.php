<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    private $customerRepository;
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->middleware('auth:admin');
        $this->customerRepository = $customerRepository;
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->customerRepository->store($request->validated());
        return response()->json("Item created successfully with id : $customer->id");
    }

    public function update(UpdateCustomerRequest $request)
    {
        $customer = $this->customerRepository->update($request->validated());
        return response()->json("Item with id : $customer->id updated successfully");
    }

    public function delete($id)
    {
        $customer = $this->customerRepository->delete($id);
        return response()->json("Item with id : $customer->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->customerRepository->getCustomers($text, request()->page_size, request()->status);
    }
    public function toggleStatus($id)
    {
        $customer = $this->customerRepository->toggleStatus($id);
        return response()->json("Status of item with id : $customer->id updated successfully");
    }
    public function find($id)
    {
        return $this->customerRepository->getCustomer($id);
    }
}
