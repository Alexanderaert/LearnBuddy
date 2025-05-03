<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function store(StoreBookingRequest $request, $mentorId): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $booking = $this->bookingService->createBooking($request->validated(), $mentorId, $user->id);
        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }
}
