<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Customer;

class CustomerRepository
{
    public function store($input)
    {
        return Customer::create($input);
    }
    public function update($input)
    {
        $customer = $this->find($input["id"]);
        $customer->update($input);
        return $customer;
    }
    public function delete($id)
    {
        $customer = $this->find($id);
        $customer->delete();
        return $customer;
    }
    public function getCustomers($text, $page_size, $status)
    {
        if ($page_size) {
            return Customer::where("name", "like", "%" . strtolower($text) . '%')
                ->when($status !== null, function ($q) use ($status) {
                    $q->where("banned", $status);
                })
                ->with("mediaManager")
                ->orderBy("id", "desc")->paginate($page_size);
        }
        return Customer::get();
    }
    public function toggleStatus($id)
    {
        $customer = $this->find($id);
        $customer->banned = $customer->banned == 1 ? 0 : 1;
        $customer->save();
        return $customer;
    }
    public function getCustomer($id)
    {
        return $this->find($id, ["mediaManager"]);
    }
    //Commons
    private function find($id, $relations = [])
    {
        $customer = Customer::with($relations)->find($id);
        if (!$customer) throw  new NotFoundException;
        return $customer;
    }
}
