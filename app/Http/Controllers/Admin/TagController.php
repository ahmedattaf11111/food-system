<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;
use App\Repositories\TagRepository;

class TagController extends Controller
{
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        // $this->middleware('auth:admin');
        $this->tagRepository = $tagRepository;
    }

    public function store(StoreTagRequest $request)
    {
        $tag = $this->tagRepository->store($request->validated());
        return response()->json("Item created successfully with id : $tag->id");
    }

    public function update(UpdateTagRequest $request)
    {
        $tag = $this->tagRepository->update($request->validated());
        return response()->json("Item with id : $tag->id updated successfully");
    }

    public function delete($id)
    {
        $tag = $this->tagRepository->delete($id);
        return response()->json("Item with id : $tag->id deleted successfully");
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->tagRepository->getAllTages($text, request()->page_size);
    }

    public function find($id)
    {
        return $this->tagRepository->getTag($id);
    }

    
}
