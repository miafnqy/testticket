<?php

use \Symfony\Component\HttpFoundation\Response;
use \App\Models\User;
use \App\Models\Role;
use \App\Enums\UserRole;
use function Pest\Laravel\actingAs;

it('unauthorized users can\'t access /api/roles', function () {
    $this->getJson('/api/roles')
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('/api/roles returns a list of roles', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $this->getJson('/api/roles')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'priority',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
});

it('users can\'t create user roles', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/roles', [])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('managers can\'t create user roles', function () {
    $user = User::factory()->for(Role::find(UserRole::MANAGER))->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/roles', [])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('admins can create user roles', function () {
    $admin = User::factory()->for(Role::find(UserRole::ADMIN))->create();

    actingAs($admin, 'sanctum');

    $this->postJson('/api/roles', [
        'name' => fake()->unique()->jobTitle(),
        'priority' => fake()->numberBetween(1,9),
    ])
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'priority',
                'created_at',
                'updated_at',
            ]
        ]);
});
