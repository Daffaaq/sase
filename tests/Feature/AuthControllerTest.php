<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_login_request()
    {
        $response = $this->post('/login-username/post', []);
        $response->assertSessionHasErrors(['username', 'password']);

        $response = $this->post('/login-username/post', ['username' => 'user']);
        $response->assertSessionHasErrors(['password']);

        $response = $this->post('/login-username/post', ['username' => 'user', 'password' => 'short']);
        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function it_logs_in_successfully()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'role' => 'kadiv'
        ]);

        $response = $this->post('/login-username/post', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboardkadiv');
        $this->assertAuthenticatedAs($user);
        $this->assertEquals($user->uuid, session('uuid'));
    }

    /** @test */
    public function it_shows_error_for_invalid_credentials()
    {
        $response = $this->post('/login-username/post', [
            'username' => 'nonexistentuser',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['username' => 'Username atau password tidak sesuai']);
    }
}
