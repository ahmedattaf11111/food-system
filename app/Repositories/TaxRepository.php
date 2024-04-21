<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Tax;

class TaxRepository
{
    public function store($input)
    {
        return Tax::create($input);
    }
    public function update($input)
    {
        $tax = $this->find($input["id"]);
        $tax->update($input);
        return $tax;
    }

    public function delete($id)
    {
        $tax = $this->find($id);
        $tax->delete();
        return $tax;
    }
    public function getAllTaxes($text, $page_size, $status)
    {
        if ($page_size) {
            return Tax::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->when($status !== null, function ($q) use ($status) {
                $q->where("status", $status);
            })
                ->orderBy("id", "desc")->paginate($page_size);
        }
        return Tax::get();
    }
    public function toggleStatus($id)
    {
        $tax = $this->find($id);
        $tax->status = $tax->status == 1 ? 0 : 1;
        $tax->save();
        return $tax;
    }
    public function getTax($id)
    {
        return $this->find($id);
    }
    //Commons
    private function find($id)
    {
        $tax = Tax::find($id);
        if (!$tax) throw  new NotFoundException;
        return $tax;
    }
    
}
