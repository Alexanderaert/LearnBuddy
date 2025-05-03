<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function createBooking(array $data, int $mentorId, int $studentId): Booking
    {
        return DB::transaction(function () use ($data, $mentorId, $studentId) {
            $mentor = User::where('is_mentor', true)->findOrFail($mentorId);
            $student = User::findOrFail($studentId);

            $schedule = Schedule::where('mentor_id', $mentorId)
                ->where('start_time', $data['start_time'])
                ->where('end_time', $data['end_time'])
                ->where('is_booked', false)
                ->first();

            if (!$schedule) {
                throw ValidationException::withMessages([
                    'start_time' => ['The selected time slot is not available.'],
                ]);
            }

            $overlappingBooking = Booking::where('mentor_id', $mentorId)
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                        ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                        ->orWhere(function ($q) use ($data) {
                            $q->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                        });
                })
                ->exists();

            if ($overlappingBooking) {
                throw ValidationException::withMessages([
                    'start_time' => ['The selected time slot is already booked.'],
                ]);
            }

            $schedule->update(['is_booked' => true]);

            return Booking::create([
                'mentor_id' => $mentor->id,
                'student_id' => $student->id,
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => 'pending',
            ]);
        });
    }
}
