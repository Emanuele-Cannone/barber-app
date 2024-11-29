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

        $roles = collect([
            'Super-Admin',
            'Admin',
            'Barber'
        ]);

        $roles->each(function ($role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        });

        $barbers = User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Emanuele',
            'email' => 'admin@admin.com',
            'password' => Hash::make('emanuele'),
        ]);

        $admin->assignRole(['Super-Admin']);

        $barbers->each(function ($barber) {
            $barber->assignRole(['Barber']);
        });

    }
}
