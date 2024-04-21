<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\MediaManagerRepository;
use Illuminate\Support\Facades\Storage;

class MediaManagerController extends Controller
{
    private $mediaManagerRepository;
    public function __construct(MediaManagerRepository $mediaManagerRepository)
    {
        $this->middleware('auth:admin');
        $this->mediaManagerRepository = $mediaManagerRepository;
    }
    public function store()
    {
        $this->mediaManagerRepository->store(request()->images);
        return response()->json("All media are stored successfully");
    }
    public function deleteMedia($id)
    {
        $media = $this->mediaManagerRepository->deleteMedia($id);
        Storage::delete($media->hash_name);
        return response()->json("Item with id : $media->id deleted successfully and its image if exist");
    }
    public function getAllMedia()
    {
        return $this->mediaManagerRepository->getAllMedia(
            request()->text,
            request()->recent_limit,
            request()->prev_limit
        );
    }
}
