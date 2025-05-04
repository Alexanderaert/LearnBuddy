<?php
// src/app/Http/Controllers/Api/MentorProfileController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MentorProfileController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $mentors = User::where('is_mentor', true)
            ->when($request->min_rating, fn($query, $rating) => $query->where('average_rating', '>=', $rating))
            ->with(['skills'])
            ->paginate(10);

        return response()->json([
            'message' => 'Mentors retrieved successfully',
            'data' => $mentors,
        ]);
    }

    public function show($id): JsonResponse
    {
        $mentor = User::where('is_mentor', true)
            ->with(['skills'])
            ->findOrFail($id);

        return response()->json([
            'message' => 'Mentor profile retrieved successfully',
            'data' => $mentor,
        ]);
    }

    public function bookings(): JsonResponse
    {
        Gate::authorize('is-mentor');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bookings = Booking::where('mentor_id', $user->id)
            ->with(['student:id,name'])
            ->paginate(10);

        return response()->json([
            'message' => 'Mentor bookings retrieved successfully',
            'data' => $bookings,
        ]);
    }

    public function reviews(): JsonResponse
    {
        Gate::authorize('is-mentor');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $reviews = Review::where('mentor_id', $user->id)
            ->with(['student:id,name'])
            ->paginate(10);

        return response()->json([
            'message' => 'Mentor reviews retrieved successfully',
            'data' => $reviews,
        ]);
    }

    public function storeSchedule(StoreScheduleRequest $request): JsonResponse
    {
        Gate::authorize('is-mentor');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $schedule = Schedule::create([
            'mentor_id' => $user->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_booked' => false,
        ]);

        return response()->json([
            'message' => 'Schedule slot created successfully',
            'data' => $schedule,
        ], 201);
    }

    public function mentorSchedule($id): JsonResponse
    {
        $schedules = Schedule::where('mentor_id', $id)
            ->where('is_booked', false)
            ->where('start_time', '>=', now())
            ->get();

        return response()->json([
            'message' => 'Mentor schedule retrieved successfully',
            'data' => $schedules,
        ]);
    }

    public function top(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);
        $sortBy = $request->query('sort_by', 'rating');

        $query = User::where('is_mentor', true)
            ->with(['skills']);

        if ($sortBy === 'rating') {
            $query->orderByDesc('average_rating');
        } elseif ($sortBy === 'bookings') {
            $query->withCount('bookingsAsMentor')->orderByDesc('bookings_as_mentor_count');
        }

        $mentors = $query->take($limit)->get();

        return response()->json([
            'message' => 'Top mentors retrieved successfully',
            'data' => $mentors,
        ]);
    }

    public function recommended(Request $request): JsonResponse
    {
        $skills = $request->query('skills') ? explode(',', $request->query('skills')) : [];

        $mentors = User::where('is_mentor', true)
            ->when($skills, function ($query, $skills) {
                $query->whereHas('skills', fn($q) => $q->whereIn('name', $skills));
            })
            ->with(['skills'])
            ->orderByDesc('average_rating')
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Recommended mentors retrieved successfully',
            'data' => $mentors,
        ]);
    }
}
