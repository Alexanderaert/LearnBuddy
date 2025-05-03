<?php
// tests/Unit/Controllers/AuthControllerTest.php
namespace Tests\Unit\Controllers;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_register_creates_user_and_token()
    {
        $request = Request::create('/api/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_mentor' => true,
        ]);

        $controller = new AuthController();
        $response = $controller->register($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertNotNull($response->getData()->data->token);
    }
}
