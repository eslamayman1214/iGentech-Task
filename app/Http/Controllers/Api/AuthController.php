<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\OtpService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(private OtpService $otpService)
    {
    }

    /**
     * Request an OTP for phone-based login.
     *
     * @param SendOtpRequest $request
     * @return JsonResponse
     */
    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $phone = $request->input('phone');

        try {
            $otp = $this->otpService->generateOtp($phone);
            $this->otpService->sendOtp($phone, $otp);

            return response()->json(['message' => 'OTP sent successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to send OTP', ['phone' => $phone, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to send OTP.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify the OTP and log in the user.
     *
     * @param VerifyOtpRequest $request
     * @return JsonResponse
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $phone = $request->input('phone');
        $otp = $request->input('otp');

        try {
            // Verify OTP
            $user = $this->otpService->verifyOtp($phone, $otp);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Generate access token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to verify OTP', ['phone' => $phone, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to verify OTP.', 'error' => $e->getMessage()], 500);
        }
    }
}
