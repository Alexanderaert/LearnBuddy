<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class MentorProfileController extends Controller
{
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
}
