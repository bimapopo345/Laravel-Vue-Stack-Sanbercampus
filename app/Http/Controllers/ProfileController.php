<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    /**
     * View profile
     */
    // app/Http/Controllers/ProfileController.php

public function show()
{
    try {
        $user = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Token tidak valid atau tidak disediakan'], 401);
    }

    $profile = $user->profile;

    if (!$profile) {
        return response()->json(['message' => 'Profile not found.'], 404);
    }

    return response()->json([
        'user' => $user,
        'profile' => $profile,
    ], 200);
}


    /**
     * Update profile
     */
    // app/Http/Controllers/ProfileController.php

public function update(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Token tidak valid atau tidak disediakan'], 401);
    }

    $profile = $user->profile;

    if (!$profile) {
        return response()->json(['message' => 'Profile not found.'], 404);
    }

    // Validasi input
    $validator = Validator::make($request->all(), [
        'bio' => 'nullable|string',
        'age' => 'nullable|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Kumpulkan data yang akan diperbarui
    $data = $request->only(['bio', 'age']);

    // Handle image upload
    if ($request->hasFile('image')) {
        try {
            // Mengupload gambar ke Cloudinary
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'profiles', // Opsional: Tentukan folder di Cloudinary
            ]);
            $uploadedFileUrl = $uploadedFile->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengupload gambar.'], 500);
        }
    }

    // Perbarui profil
    $profile->update($data);

    // Reload data profil dan pengguna untuk respons yang akurat
    $user->refresh();
    $profile->refresh();

    return response()->json([
        'message' => 'Profile updated successfully.',
        'user' => $user,
        'profile' => $profile
    ], 200);
}

}
