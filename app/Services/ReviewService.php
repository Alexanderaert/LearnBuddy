<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function createReview(array $data, int $studentId): Review
    {
        return DB::transaction(function () use ($data, $studentId) {
            $mentor = User::where('is_mentor', true)->findOrFail($data['mentor_id']);
            $student = User::findOrFail($studentId);

            return Review::create([
                'mentor_id' => $mentor->id,
                'student_id' => $student->id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);
        });
    }
}
