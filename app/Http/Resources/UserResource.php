<?php
// src/app/Http/Resources/UserResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_mentor' => $this->is_mentor,
            'average_rating' => $this->average_rating ? (float) $this->average_rating : null,
            'skills' => $this->whenLoaded('skills', fn() => $this->skills->pluck('name')),
            'reviews' => $this->whenLoaded('reviews', fn() => $this->reviews->map(fn($review) => [
                'id' => $review->id,
                'comment' => $review->comment,
                'rating' => $review->rating,
                'student' => $review->student ? ['id' => $review->student->id, 'name' => $review->student->name] : null,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
