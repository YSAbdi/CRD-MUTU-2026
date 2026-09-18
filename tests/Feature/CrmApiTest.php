<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CrmApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_login(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.test', 'password' => 'secret-password-123', 'role' => 'superadmin']);
        $response = $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'secret-password-123']);
        $response->assertOk()->assertJsonStructure(['user', 'token']);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->getJson('/api/v1/dashboard')->assertUnauthorized();
    }
}
