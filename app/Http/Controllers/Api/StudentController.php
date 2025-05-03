<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    public function bookings(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bookings = Booking::where('student_id', $user->id)
            ->with(['mentor:id,name'])
            ->paginate(10);

        return response()->json([
            'message' => 'Bookings retrieved successfully',
            'data' => $bookings,
        ]);
    }
}
