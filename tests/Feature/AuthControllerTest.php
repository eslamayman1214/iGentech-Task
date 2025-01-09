<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_otp_success()
    {
        $response = $this->postJson('/api/auth/send-otp', [
            'phone' => '+201027091255',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'OTP sent successfully.']);
    }

    public function test_verify_otp_success()
    {
        // Simulate OTP generation
        $phone = '+201027091255';
        $otp = 729778;
        cache()->put('otp_' . $phone, $otp, now()->addMinutes(5));

        $response = $this->postJson('/api/auth/verify-otp', [
            'phone' => $phone,
            'otp' => $otp,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'access_token', 'user']);
    }

    public function test_verify_otp_invalid_otp()
    {
        $response = $this->postJson('/api/auth/verify-otp', [
            'phone' => '+201027091255',
            'otp' => 111111, // Invalid OTP
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid or expired OTP']);
    }
}
