<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect(UserRole::cases())
            ->map(function ($role) {
                return Role::factory()->create(['name' => $role->value, 'priority' => $role->priority()]);
            });

         User::factory(100)->recycle($roles)->create();
    }
}
