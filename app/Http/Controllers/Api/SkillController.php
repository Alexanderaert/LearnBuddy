<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SkillController extends Controller
{
    public function index(): JsonResponse
    {
        Gate::authorize('is-mentor');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $skills = $user->skills()->get();

        return response()->json([
            'message' => 'Skills retrieved successfully',
            'data' => $skills,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('is-mentor');

        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
        ]);

        $skill = Skill::create(['name' => $request->name]);
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->skills()->attach($skill->id);

        return response()->json([
            'message' => 'Skill added successfully',
            'data' => $skill,
        ], 201);
    }

    public function destroy($id): JsonResponse
    {
        Gate::authorize('is-mentor');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $skill = $user->skills()->findOrFail($id);
        $user->skills()->detach($id);

        return response()->json([
            'message' => 'Skill removed successfully',
        ]);
    }
}
