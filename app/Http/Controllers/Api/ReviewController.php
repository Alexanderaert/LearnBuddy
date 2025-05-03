<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $review = $this->reviewService->createReview($request->validated(), $user->id);
        return response()->json([
            'message' => 'Review created successfully',
            'data' => $review,
        ], 201);
    }
}
