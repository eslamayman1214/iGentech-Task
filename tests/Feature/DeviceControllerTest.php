<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_device()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/devices', [
            'user_id' => $user->id,
            'device_type' => 'mobile',
            'device_id' => 'device123',
            'fcm_token' => 'unique_fcm_token',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'user_id', 'device_type', 'fcm_token']);
    }

    public function test_get_devices()
    {
        Device::factory()->count(3)->create();

        $response = $this->getJson('/api/devices');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_delete_device()
    {
        $device = Device::factory()->create();

        $response = $this->deleteJson("/api/devices/{$device->id}");

        $response->assertStatus(204);
    }
}
