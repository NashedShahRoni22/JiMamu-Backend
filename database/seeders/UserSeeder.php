<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole =  Role::create([
            'name' => 'super-admin',
        ]);
        $adminRole = Role::create([
            'name' => 'admin',
        ]);
        $riderRole = Role::create([
            'name' => 'rider',
        ]);
        $userRole = Role::create([
            'name' => 'user',
        ]);

        $superAdmin = User::create([
            'name' => 'super-admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin1@admin.com',
            'password' => Hash::make('password'),
        ]);
        $rider = User::create([
            'name' => 'rider',
            'email' => 'rider@rider.com',
            'password' => Hash::make('password'),
        ]);
        $user = User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole($superAdminRole);
        $admin->assignRole($adminRole);
        $rider->assignRole($riderRole);
        $user->assignRole($userRole);
    }
}
