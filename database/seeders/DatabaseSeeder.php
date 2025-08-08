<?php

namespace Database\Seeders;

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
        $roles = collect([
            [
                'name' => 'admin',
                'priority' => 0
            ],
            [
                'name' => 'manager',
                'priority' => 1
            ],
            [
                'name' => 'user',
                'priority' => 2
            ]
        ])
            ->map(function ($role) {
                return Role::factory()->create($role);
            });

         User::factory(100)->recycle($roles)->create();
    }
}
