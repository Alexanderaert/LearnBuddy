<?php
// database/factories/MaterialFactory.php
namespace Database\Factories;

use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition()
    {
        return [
            'mentor_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'file_path' => 'materials/' . $this->faker->word . '.pdf',
            'url' => null,
            'category' => $this->faker->word,
        ];
    }
}
