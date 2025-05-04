<?php
// tests/Feature/RatingTest.php
namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentor_rating_updates_on_review_creation()
    {
        /** @var User $mentor */
        $mentor = User::factory()->create(['is_mentor' => true]);
        /** @var User $student */
        $student = User::factory()->create(['is_mentor' => false]);

        $this->actingAs($student);

        $this->postJson('/api/reviews', [
            'mentor_id' => $mentor->id,
            'comment' => 'Great mentor!',
            'rating' => 5,
        ]);

        $mentor->refresh();
        $this->assertEquals(5.0, $mentor->average_rating);

        $this->postJson('/api/reviews', [
            'mentor_id' => $mentor->id,
            'comment' => 'Good mentor!',
            'rating' => 3,
        ]);

        $mentor->refresh();
        $this->assertEquals(4.0, $mentor->average_rating);
    }

    public function test_get_mentors_includes_rating()
    {
        $mentor = User::factory()->create(['is_mentor' => true, 'average_rating' => 4.5]);

        $response = $this->getJson('/api/mentors');

        $response->assertStatus(200)
            ->assertJsonFragment(['average_rating' => 4.5]);
    }

    public function test_get_mentors_with_min_rating_filter()
    {
        $mentor1 = User::factory()->create(['is_mentor' => true, 'average_rating' => 4.5]);
        $mentor2 = User::factory()->create(['is_mentor' => true, 'average_rating' => 3.5]);

        $response = $this->getJson('/api/mentors?min_rating=4.0');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $mentor1->id])
            ->assertJsonMissing(['id' => $mentor2->id]);
    }

    public function test_get_top_mentors()
    {
        $mentor1 = User::factory()->create(['is_mentor' => true, 'average_rating' => 4.8]);
        $mentor2 = User::factory()->create(['is_mentor' => true, 'average_rating' => 4.2]);

        $response = $this->getJson('/api/mentors/top?limit=2&sort_by=rating');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $mentor1->id, 'average_rating' => 4.8])
            ->assertJsonFragment(['id' => $mentor2->id, 'average_rating' => 4.2]);
    }

    public function test_get_recommended_mentors()
    {
        $mentor = User::factory()->create(['is_mentor' => true, 'average_rating' => 4.5]);
        $skill = Skill::factory()->create(['name' => 'Python']);
        $mentor->skills()->attach($skill->id);

        $response = $this->getJson('/api/mentors/recommended?skills=Python');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $mentor->id, 'average_rating' => 4.5]);
    }
}
