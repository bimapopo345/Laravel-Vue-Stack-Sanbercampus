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
    public function show()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $profile = $user->profile;

        if(!$profile){
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        return response()->json($profile, 200);
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $profile = $user->profile;

        if(!$profile){
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'bio' => 'nullable|string',
            'age' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Handle image upload
        if($request->hasFile('image')){
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $profile->image = $uploadedFileUrl;
        }

        // Update other fields
        $profile->update($request->only(['bio', 'age']));

        return response()->json(['message' => 'Profile updated successfully.', 'profile' => $profile], 200);
    }
}
