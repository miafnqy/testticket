<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect(UserRole::cases())
            ->map(function ($role) {
                return Role::factory()->create(['name' => $role->name(), 'priority' => $role->priority()]);
            });

        User::factory()->create(['role_id' => 1, 'name' => 'admin', 'email' => 'admin@admin.com', 'password' => Hash::make('password')]);

         User::factory(10000)->recycle($roles)->create();
    }
}
