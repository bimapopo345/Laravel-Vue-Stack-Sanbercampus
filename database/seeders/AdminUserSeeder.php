<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Ambil role admin
        $adminRole = Role::where('name', 'admin')->first();

        // Buat 1 akun admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now()
        ]);

        // Buat profil untuk admin
        Profile::create([
            'user_id' => $admin->id,
            'bio' => 'Administrator',
            'age' => 30,
            'image' => 'https://via.placeholder.com/150'
        ]);
    }
}
