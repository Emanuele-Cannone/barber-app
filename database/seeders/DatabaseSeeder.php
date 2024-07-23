<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $roles = collect([
            'Super-Admin',
            'Admin',
            'Barber',
            'Customer'
        ]);

        $roles->each(function ($role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        });

        $barbers = User::factory(10)->create();
        $users = User::factory(30)->create();

        $admin = User::factory()->create([
            'name' => 'Emanuele',
            'email' => 'admin@admin.com',
            'password' => Hash::make('emanuele'),
        ]);

        $admin->assignRole(['Super-Admin']);

        $barbers->each(function ($barber) {
            $barber->assignRole(['Barber']);
        });

        $users->each(function ($user) {
            $user->assignRole(['Customer']);
        });
    }
}
