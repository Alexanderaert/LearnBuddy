<?php
// src/database/factories/BookingFactory.php
namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'student_id' => User::factory()->create(['is_mentor' => false])->id,
            'mentor_id' => User::factory()->create(['is_mentor' => true])->id,
            'start_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_time' => $this->faker->dateTimeBetween('+1 hour', '+2 hours'),
        ];
    }
}
