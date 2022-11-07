<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "like" => $this->isUserLiked(Auth::user()),
            'title' => $this->title,
            'description' => $this->description,
            'genre' => $this->genre,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'comments' => $this->comments,
            'likes' => new LikeResource($this->whenLoaded('likes')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}