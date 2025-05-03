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
        $mentor = User::create([
            'name' => 'Mentor',
            'email' => 'mentor@example.com',
            'password' => bcrypt('password123'),
            'is_mentor' => true,
        ]);
        $student = User::create([
            'name' => 'Student',
            'email' => 'student@example.com',
            'password' => bcrypt('password123'),
            'is_mentor' => false,
        ]);
        $schedule = Schedule::create([
            'mentor_id' => $mentor->id,
            'start_time' => '2025-05-04 10:00:00',
            'end_time' => '2025-05-04 11:00:00',
        ]);

        $token = $student->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson("/api/mentors/{$mentor->id}/book", [
            'start_time' => '2025-05-04 10:00:00',
            'end_time' => '2025-05-04 11:00:00',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', [
            'student_id' => $student->id,
            'mentor_id' => $mentor->id,
            'start_time' => '2025-05-04 10:00:00',
        ]);
    }
}
