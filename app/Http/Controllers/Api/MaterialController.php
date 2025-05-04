<?php
// app/Http/Controllers/Api/MaterialController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('upload-material');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,mp4|max:102400', // 100MB max
            'url' => 'nullable|url',
            'category' => 'required|string|max:255',
            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'exists:skills,id',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        $material = Material::create([
            'mentor_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'url' => $validated['url'],
            'category' => $validated['category'],
        ]);

        if (!empty($validated['skill_ids'])) {
            $material->skills()->attach($validated['skill_ids']);
        }

        return response()->json([
            'message' => 'Material uploaded successfully',
            'data' => $material->load('skills'),
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Студенты видят материалы менторов, с которыми есть бронирование
        $mentorIds = $user->bookingsAsStudent()->pluck('mentor_id')->unique();
        $materials = Material::whereIn('mentor_id', $mentorIds)
            ->with('skills')
            ->get();

        return response()->json([
            'message' => 'Materials retrieved successfully',
            'data' => $materials,
        ]);
    }

    public function download(Material $material)
    {
        Gate::authorize('access-material', $material);

        if (!$material->file_path) {
            return response()->json(['message' => 'No file available'], 404);
        }

        return Storage::disk('public')->download($material->file_path);
    }
}
