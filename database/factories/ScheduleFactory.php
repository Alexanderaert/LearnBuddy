<?php
// src/database/factories/ScheduleFactory.php
namespace Database\Factories;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition()
    {
        return [
            'mentor_id' => User::factory()->create(['is_mentor' => true])->id,
            'start_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_time' => $this->faker->dateTimeBetween('+1 hour', '+2 hours'),
        ];
    }
}