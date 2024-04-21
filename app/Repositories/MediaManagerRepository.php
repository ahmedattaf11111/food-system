<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\MediaManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MediaManagerRepository
{
    public function store($imageFiles)
    {
        $mediaList = [];
        foreach ($imageFiles as $image) {
            $hash_name = $image->store("");
            $mediaList[] = [
                "name" => $image->getClientOriginalName(),
                "hash_name" => $hash_name,
                "extension" => $image->extension(),
                "created_at" => Carbon::now()
            ];
        }
        MediaManager::insert($mediaList);
    }
    public function deleteMedia($id)
    {
        $media = $this->find($id);
        $media->delete();
        return $media;
    }
    public function getAllMedia($text, $recent_limit, $prev_limit)
    {
        $recentUploadedFiles = MediaManager::latest()->take($recent_limit)->get();
        $previousUploadedFiles = MediaManager::when($text, function ($q) use ($text) {
            $q->where("name", "like", "%" . $text . "%");
        })->whereNotIn("id", $recentUploadedFiles->pluck("id"))->take($prev_limit)
            ->orderByDesc("id")->get();
        return [
            "recent_uploaded_files" => $recentUploadedFiles,
            "previously_uploaded_files" => $previousUploadedFiles
        ];
    }
    //Commons
    private function find($id, $relations = [])
    {
        $media = MediaManager::with($relations)->find($id);
        if (!$media) throw  new NotFoundException;
        return $media;
    }
}
