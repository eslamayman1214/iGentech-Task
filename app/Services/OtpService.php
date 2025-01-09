<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;

class OtpService
{
    /**
     * Generate an OTP and store it in the cache.
     *
     * @param string $phone
     * @return int
     */
    public function generateOtp(string $phone): int
    {
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));
        return $otp;
    }

    /**
     * Send the OTP via Twilio SMS.
     *
     * @param string $phone
     * @param int $otp
     * @return void
     * @throws \Exception
     */
    public function sendOtp(string $phone, int $otp): void
    {
        $twilio = new Client(
            config('twilio.sid'),
            config('twilio.auth_token')
        );

        $twilio->messages->create($phone, [
            'from' => config('twilio.phone'),
            'body' => "Your OTP is: $otp",
        ]);
    }
    /**
     * Verify the OTP and fetch the associated user.
     *
     * @param string $phone
     * @param int $otp
     * @return User|null
     * @throws Exception
     */
    public function verifyOtp(string $phone, int $otp): ?User
    {
        // Retrieve OTP from cache
        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp || $cachedOtp != $otp) {
            throw new Exception('Invalid or expired OTP');
        }

        // Fetch the user
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            throw new Exception('User not found');
        }

        // Clear the OTP from cache
        Cache::forget('otp_' . $phone);

        return $user;
    }
}
