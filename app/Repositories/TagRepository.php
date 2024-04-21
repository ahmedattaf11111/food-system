<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Tag;

class TagRepository
{
    public function store($input)
    {
        return Tag::create($input);
    }
    public function update($input)
    {
        $tag = $this->find($input["id"]);
        $tag->update($input);
        return $tag;
    }

    public function delete($id)
    {
        $tag = $this->find($id);
        $tag->delete();
        return $tag;
    }
    public function getAllTages($text, $page_size)
    {
        if ($page_size) {
            return Tag::where(function ($q) use ($text) {
                $q->where("name_ar", "like", "%" . strtolower($text) . '%')
                    ->orWhere("name_en", "like", "%" . strtolower($text) . '%');
            })->orderBy("id", "desc")->paginate($page_size);
        }
        return Tag::get();
    }
    public function getTag($id)
    {
        return $this->find($id);
    }
    //Commons
    private function find($id)
    {
        $tag = Tag::find($id);
        if (!$tag) throw  new NotFoundException;
        return $tag;
    }
}
