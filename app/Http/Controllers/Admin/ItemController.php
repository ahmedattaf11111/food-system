<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Dashboard endpoints
    public function store(StoreItemRequest $request)
    {
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $employee = Item::create($request->input());
        return $employee;
    }

    public function update(UpdateItemRequest $request)
    {
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->_update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_item"];
    }

    public function delete($id)
    {
        $oldImage = $this->_delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        if (request()->page_size) {
            return Item::whereRaw('LOWER(`title_ar`) LIKE ? or LOWER(`title_en`) LIKE ?', [
                "%" . strtolower($text) . '%',
                "%" . strtolower($text) . '%',
            ])->orderBy("id", "desc")->paginate(request()->page_size);
        }
        return Item::get();
    }
    //Commons    
    public function _update($itemInput)
    {
        $item = Item::find($itemInput["id"]);
        $oldImage = $item->getImageName();
        $itemInput["image"] = isset($itemInput["image"]) ? $itemInput["image"] : $oldImage;
        $item->update($itemInput);
        return ["old_image" => $oldImage, "updated_item" => $item];
    }
    public function _delete($id)
    {
        $item = Item::find($id);
        $oldImage = null;
        if ($item) {
            $oldImage = $item->getImageName();
            $item->delete();
        }
        return $oldImage;
    }
}
