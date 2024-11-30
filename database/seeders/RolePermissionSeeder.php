<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = [
            'manage categories',
            'manage tools',
            'manage projects',
            'manage project tools',
            'manage applicants',
            'manage wallets',

            // other permissions
            'apply job',
            'topup wallet',
            'withdraw wallet',
        ];

        foreach ($permissions as $permission){
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        $clientRole = Role::firstOrCreate([
            'name' => 'project_client'
        ]);
        $clientPermissions = [
            'manage projects',
            'manage project tools',
            'topup wallet',
            'withdraw wallet',
        ];

        $clientRole->syncPermissions($clientPermissions);

        $freelancerRole = Role::firstOrCreate([
            'name' => 'project_freelancer'
        ]);
        $freelancerPermissions = [
            'apply job',
            'withdraw wallet',
        ];
        $freelancerRole->syncPermissions($freelancerPermissions);

        $superadminRole = Role::firstOrCreate([
            'name' => 'super_admin'
        ]);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mail.com',
            'occupation' => 'Super Admin',
            'connect' => 9999,
            'avatar' => 'images/default.png',
            'password' => bcrypt('password')
        ]);

        $user->assignRole($superadminRole);
        $wallet = new Wallet([
            'balance' => 0
        ]);
        $user->wallet()->save($wallet);
    }
}
