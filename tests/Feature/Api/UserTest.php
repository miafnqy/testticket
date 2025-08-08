<?php

use function Pest\Laravel\actingAs;
use \Symfony\Component\HttpFoundation\Response;
use \App\Models\User;
use \App\Models\Role;
use \App\Enums\UserRole;

beforeEach(function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed');
});

it('unauthorized users can\'t access /api/users', function () {
    $response = $this->get('/api/users', [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
});

it ('/api/users endpoint returns a users list', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $response = $this->get('/api/users')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
});

it ('only authorized users can create a new user', function () {
    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it ('only an admin or a manager can create a new user', function () {
    $user = User::factory()->for(Role::where('name', UserRole::USER->value)->first())->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'role_id' => UserRole::USER->priority(),
    ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it ('a user can create a new user', function () {
    $user = User::factory()->for(Role::where('name', UserRole::ADMIN->value)->first())->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'role_id' => \App\Models\Role::factory()->create()->id,
    ])
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ]
        ]);
});

it ('unauthorized user can\'t update a user', function () {
    $user = User::factory()->create();

    $this->putJson('/api/users/' . $user->id, [
        'name' => fake()->name . '_updated',
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it ('a user can update a user', function () {
    $user = User::factory()->create();
    $originalName = $user->name;

    actingAs($user, 'sanctum');

    $response = $this->putJson('/api/users/' . $user->id, [
        'name' => fake()->name . '_updated',
    ]);

    $this->assertNotEquals($originalName, $response->content('name'));
});








