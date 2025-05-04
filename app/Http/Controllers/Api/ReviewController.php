<?php
// src/app/Http/Controllers/Api/ReviewController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mentor_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'mentor_id' => $validated['mentor_id'],
            'student_id' => $request->user()->id,
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
        ]);

        return response()->json([
            'message' => 'Review created successfully',
            'data' => $review,
        ], 201);
    }
}
