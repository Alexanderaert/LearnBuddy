<?php
// src/database/factories/SkillFactory.php
namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Python', 'PHP', 'JavaScript', 'Java', 'C++']),
        ];
    }
}
