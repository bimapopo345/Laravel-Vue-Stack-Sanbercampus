<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpCode;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Profile;


class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if($validator->fails()){
        return response()->json($validator->errors(), 422);
    }

    // Mengambil peran 'user'
    $role = Role::where('name', 'user')->first();

    // Membuat pengguna
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $role->id,
    ]);

    // Membuat profil kosong untuk pengguna
    Profile::create([
        'user_id' => $user->id,
        'bio' => null,
        'age' => null,
        'image' => null,
    ]);

    // Menghasilkan OTP
    $otp = mt_rand(100000, 999999);
    $otpCode = OtpCode::create([
        'otp' => $otp,
        'valid_until' => Carbon::now()->addMinutes(10),
        'user_id' => $user->id,
    ]);

    // TODO: Kirim OTP via email atau SMS

    return response()->json(['message' => 'User registered successfully. Please verify your account.', 'otp' => $otp], 201);
}


    /**
     * Login user and return JWT token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Logout user (Invalidate the token)
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'User successfully logged out']);
    }

    /**
     * Get the authenticated User
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Refresh a token.
     */
    public function refresh()
    {
        return $this->createNewToken(JWTAuth::refresh());
    }

    /**
     * Get the token structure
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
