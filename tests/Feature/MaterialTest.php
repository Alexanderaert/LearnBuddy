<?php
// tests/Feature/MaterialTest.php
namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Material;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentor_can_upload_material()
    {
        Storage::fake('public');

        $mentor = User::factory()->create(['is_mentor' => true]);
        $skill = Skill::factory()->create(['name' => 'Python']);

        $token = $mentor->createToken('test-token')->plainTextToken;
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        $file = UploadedFile::fake()->create('python.pdf', 1000, 'application/pdf');

        $response = $this->postJson('/api/materials', [
            'title' => 'Python Basics',
            'description' => 'Introduction to Python programming',
            'file' => $file,
            'category' => 'Программирование: Основы Python',
            'skill_ids' => [$skill->id],
            'url' => null,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Material uploaded successfully',
                'data' => [
                    'title' => 'Python Basics',
                    'category' => 'Программирование: Основы Python',
                ],
            ]);

        $this->assertDatabaseHas('materials', [
            'mentor_id' => $mentor->id,
            'title' => 'Python Basics',
        ]);

        $this->assertDatabaseHas('material_skill', [
            'skill_id' => $skill->id,
        ]);

        // Get the stored file name from the database
        $material = Material::first();
        Storage::disk('public')->assertExists($material->file_path);
    }

    public function test_student_can_access_materials_with_booking()
    {
        $mentor = User::factory()->create(['is_mentor' => true]);
        $student = User::factory()->create(['is_mentor' => false]);
        $material = Material::factory()->create([
            'mentor_id' => $mentor->id,
            'title' => 'Python Basics',
            'file_path' => 'materials/python.pdf',
            'category' => 'Программирование: Основы Python',
        ]);

        Booking::factory()->create([
            'student_id' => $student->id,
            'mentor_id' => $mentor->id,
            'start_time' => '2025-05-04 10:00:00',
            'end_time' => '2025-05-04 11:00:00',
            'status' => 'confirmed',
        ]);

        $token = $student->createToken('test-token')->plainTextToken;
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        $response = $this->getJson('/api/materials');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Python Basics']);
    }

    public function test_student_cannot_access_materials_without_booking()
    {
        $mentor = User::factory()->create(['is_mentor' => true]);
        $student = User::factory()->create(['is_mentor' => false]);
        Material::factory()->create([
            'mentor_id' => $mentor->id,
            'title' => 'Python Basics',
            'file_path' => 'materials/python.pdf',
            'category' => 'Программирование: Основы Python',
        ]);

        $token = $student->createToken('test-token')->plainTextToken;
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        $response = $this->getJson('/api/materials');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_student_can_download_material()
    {
        Storage::fake('public');

        $mentor = User::factory()->create(['is_mentor' => true]);
        $student = User::factory()->create(['is_mentor' => false]);
        $material = Material::factory()->create([
            'mentor_id' => $mentor->id,
            'title' => 'Python Basics',
            'file_path' => 'materials/python.pdf',
            'category' => 'Программирование: Основы Python',
        ]);

        Booking::factory()->create([
            'student_id' => $student->id,
            'mentor_id' => $mentor->id,
            'start_time' => '2025-05-04 10:00:00',
            'end_time' => '2025-05-04 11:00:00',
            'status' => 'confirmed',
        ]);

        Storage::disk('public')->put('materials/python.pdf', 'fake content');

        $token = $student->createToken('test-token')->plainTextToken;
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        $response = $this->get('/api/materials/' . $material->id . '/download');

        $response->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');
    }
}
