<?php
// tests/Feature/BookingIntegrationTest.php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_book_mentor_schedule()
    {
        $mentor = User::factory()->create(['is_mentor' => true]);
        $student = User::factory()->create(['is_mentor' => false]);

        $startTime = now()->addDays(1)->startOfHour()->format('Y-m-d H:i:s');
        $endTime = now()->addDays(1)->startOfHour()->addHours(1)->format('Y-m-d H:i:s');

        Schedule::create([
            'mentor_id' => $mentor->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'available',
        ]);

        $token = $student->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ])->postJson("/api/mentors/{$mentor->id}/book", [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bookings', [
            'student_id' => $student->id,
            'mentor_id' => $mentor->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }
}
