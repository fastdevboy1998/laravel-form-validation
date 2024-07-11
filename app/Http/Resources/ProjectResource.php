<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{

    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imagePlaceholder = "https://via.placeholder.com/640x480.png";
        return [
            'id' => $this->id,
            'name' => $this->name,
            'due_date' => (new Carbon($this->due_date))->format('Y-m-d'),
            'created_at' => (new Carbon($this->created_at))->format('Y-m-d'),
            'status' => $this->status,
            'description' => $this->description,
            'image' => $this->image_path
                ? (strpos($this->image_path, $imagePlaceholder) !== false
                    ? $this->image_path
                    : Storage::url($this->image_path))
                : null,
            'createdBy' => new UserResource($this->createdBy),
            'updatedBy' => new UserResource($this->updatedBy),

        ];
    }
}
