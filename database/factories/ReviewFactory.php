<?php
// src/database/factories/ReviewFactory.php
namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'mentor_id' => User::factory()->create(['is_mentor' => true])->id,
            'student_id' => User::factory()->create(['is_mentor' => false])->id,
            'comment' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
