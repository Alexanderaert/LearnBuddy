<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MentorResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MentorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::where('is_mentor', true);

        if ($request->has('skill')) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('name', $request->input('skill'));
            });
        }

        $mentors = $query->with('skills')->paginate(10);

        return response()->json([
            'message' => 'Mentors retrieved successfully',
            'data' => MentorResource::collection($mentors),
        ]);
    }

    public function show($id): JsonResponse
    {
        $mentor = User::where('is_mentor', true)
            ->with(['skills', 'reviewsAsMentor'])
            ->findOrFail($id);

        return response()->json([
            'message' => 'Mentor profile retrieved successfully',
            'data' => new MentorResource($mentor),
        ]);
    }
}
