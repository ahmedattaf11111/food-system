<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Unit;

class UnitRepository
{
    public function store($input)
    {
        return Unit::create($input);
    }
    public function update($input)
    {
        $unit = $this->find($input["id"]);
        $unit->update($input);
        return $unit;
    }

    public function delete($id)
    {
        $unit = $this->find($id);
        $unit->delete();
        return $unit;
    }
    public function getAllUnits($text, $page_size, $status)
    {
        if ($page_size) {
            return Unit::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->when($status !== null, function ($q) use ($status) {
                $q->where("status", $status);
            })
                ->orderBy("id", "desc")->paginate($page_size);
        }
        return Unit::get();
    }
    public function toggleStatus($id)
    {
        $unit = $this->find($id);
        $unit->status = $unit->status == 1 ? 0 : 1;
        $unit->save();
        return $unit;
    }
    public function getUnit($id)
    {
        return $this->find($id);
    }
    //Commons
    private function find($id)
    {
        $unit = Unit::find($id);
        if (!$unit) throw  new NotFoundException;
        return $unit;
    }
}
