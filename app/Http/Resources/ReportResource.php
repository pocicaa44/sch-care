<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,  
            'status' => $this->status,
            'created_at' => $this->created_at,

            'images' => $this->images->map(fn($image) => [
                'id' => $image->id,
                'url' => Storage::url($image->path),
            ]),
        ];
    }
}
