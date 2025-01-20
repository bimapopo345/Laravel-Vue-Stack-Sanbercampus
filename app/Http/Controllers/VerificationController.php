<?php

namespace App\Http\Controllers;

use App\Mail\GenerateOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Carbon\Doctrine\CarbonType;
use Illuminate\Support\Carbon as SupportCarbon;

class VerificationController extends Controller
{
    /**
     * Generate a new OTP code
     */
    public function generateOtp(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token not provided or invalid'], 401);
        }

        // Generate OTP
        $otp = mt_rand(100000, 999999);
        $otpCode = OtpCode::create([
            'otp' => $otp,
            'valid_until' => CarbonType::now()->addMinutes(10),
            'user_id' => $user->id,
        ]);

        // TODO: Send OTP via email or SMS
        Mail::to($user->email)->send(new GenerateOtpMail($user));

        return response()->json(['message' => 'OTP generated successfully.', 'otp' => $otp], 200);
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'otp' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find OTP code
        $otpCode = OtpCode::where('otp', $request->otp)->latest()->first();

        if (!$otpCode) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        if (Carbon::now()->greaterThan($otpCode->valid_until)) {
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        // Verify user
        $user = $otpCode->user;
        $user->email_verified_at = now();
        $user->save();

        // Invalidate OTPs
        OtpCode::where('user_id', $user->id)->delete();

        return response()->json(['message' => 'Account verified successfully.'], 200);
    }
}
