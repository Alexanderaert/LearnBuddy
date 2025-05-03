<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MentorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'skills' => $this->skills->pluck('name'),
            'average_rating' => $this->reviewsAsMentor->avg('rating') ?: null,
        ];
    }
}
