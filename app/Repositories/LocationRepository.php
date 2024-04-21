<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Exceptions\NotFoundException;
use App\Models\Location;

class LocationRepository
{
    public function store($input)
    {
        return Location::create($input);
    }
    public function update($input)
    {
        $location = $this->find($input["id"]);
        $location->update($input);
        return $location;
    }
    public function delete($id)
    {
        $location = $this->find($id);
        $location->delete();
        return $location;
    }
    public function getLocations($text, $page_size, $published)
    {
        if ($page_size) {
            return Location::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->when($published !== null, function ($q) use ($published) {
                $q->where("published", $published);
            })
                ->with("mediaManager")
                ->orderBy("id", "desc")
                ->orderBy("default", "desc")->paginate($page_size);
        }
        return Location::get();
    }
    public function togglePublish($id)
    {
        $location = $this->find($id);
        if ($location->default && $location->published == 1)
            throw  new GeneralException("Default location cant be hidden");
        $location->published = $location->published == 1 ? 0 : 1;
        $location->save();
        return $location;
    }
    public function toggleDefault($id)
    {
        $location = $this->find($id);
        $location->default = $location->default == 1 ? 0 : 1;
        $location->save();
        Location::where("id", "<>", $id)->update(["default" => 0]);
        return $location;
    }

    public function getLocation($id)
    {
        return $this->find($id, ["mediaManager"]);
    }
    //Commons
    private function find($id, $relations = [])
    {
        $location = Location::with($relations)->find($id);
        if (!$location) throw  new NotFoundException;
        return $location;
    }
}
