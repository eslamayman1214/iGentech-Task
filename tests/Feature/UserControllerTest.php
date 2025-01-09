<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Eslam Ayman',
            'email' => 'eslam@example.com',
            'phone' => '+201027091255',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'email', 'phone']);
    }

    public function test_get_users()
    {
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Name']);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);
    }
}
