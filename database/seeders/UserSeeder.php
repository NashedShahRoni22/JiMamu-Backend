<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
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
        ]);
        $rider1 = User::create([
            'name' => 'rider1',
            'email' => 'rider1@rider.com',
            'status' => 2,
        ]);
        $rider2 = User::create([
            'name' => 'rider2',
            'email' => 'rider2@rider.com',
            'status' => 2,
        ]);
        $rider3 = User::create([
            'name' => 'rider3',
            'email' => 'rider3@rider.com',
            'status' => 2,
        ]);
        $rider4 = User::create([
            'name' => 'rider4',
            'email' => 'rider4@rider.com',
            'status' => 2,
        ]);
        $rider5 = User::create([
            'name' => 'rider5',
            'email' => 'rider5@rider.com',
            'status' => 2,
        ]);
        $rider6 = User::create([
            'name' => 'rider6',
            'email' => 'rider6@rider.com',
            'status' => 2,
        ]);
        $rider7 = User::create([
            'name' => 'rider7',
            'email' => 'rider7@rider.com',
            'status' => 2,
        ]);
        $rider8 = User::create([
            'name' => 'rider8',
            'email' => 'rider8@rider.com',
            'status' => 2,
        ]);
        $rider9 = User::create([
            'name' => 'rider9',
            'email' => 'rider9@rider.com',
            'status' => 2,
        ]);
        $rider10 = User::create([
            'name' => 'rider10',
            'email' => 'rider10@rider.com',
            'status' => 2,
        ]);

        $user = User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole($superAdminRole);
        $admin->assignRole($adminRole);
        $rider->assignRole($riderRole);
        $rider1->assignRole($riderRole);
        $rider2->assignRole($riderRole);
        $rider3->assignRole($riderRole);
        $rider4->assignRole($riderRole);
        $rider5->assignRole($riderRole);
        $rider6->assignRole($riderRole);
        $rider7->assignRole($riderRole);
        $rider8->assignRole($riderRole);
        $rider9->assignRole($riderRole);
        $rider10->assignRole($riderRole);
        $user->assignRole($userRole);

        Redis::geoadd('rider_locations', 23.7615, 90.3671, "rider:{$rider->id}");
        Redis::geoadd('rider_locations', 23.7597, 90.3746, "rider:{$rider1->id}");
        Redis::geoadd('rider_locations', 23.7688, 90.3653, "rider:{$rider2->id}");
        Redis::geoadd('rider_locations', 23.7602, 90.3630, "rider:{$rider3->id}");
        Redis::geoadd('rider_locations', 23.7544, 90.3662, "rider:{$rider4->id}");
        Redis::geoadd('rider_locations', 23.7515, 90.3685, "rider:{$rider5->id}");
        Redis::geoadd('rider_locations', 23.7469, 90.3552, "rider:{$rider6->id}");
        Redis::geoadd('rider_locations', 23.7512, 90.3607, "rider:{$rider7->id}");
        Redis::geoadd('rider_locations', 23.7566, 90.3741, "rider:{$rider8->id}");
        Redis::geoadd('rider_locations', 23.7460, 23.7460, "rider:{$rider9->id}");
        Redis::geoadd('rider_locations', 23.7460, 23.7460, "rider:{$rider10->id}");
    }
}
