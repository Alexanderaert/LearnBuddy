<?php
// src/database/factories/UserFactory.php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password123'),
            'is_mentor' => $this->faker->boolean(),
            'average_rating' => $this->faker->boolean() ? $this->faker->randomFloat(1, 1, 5) : null,
        ];
    }
}
