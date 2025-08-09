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

    $this->get('/api/users')
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

it ('unauthorized user can\'t access /api/users/{id} endpoint', function () {
    $response = $this->get('/api/users/1', [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
});

it ('/api/users/{id} endpoint returns a user', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $this->getJson('/api/users/' . $user->id)
        ->assertStatus(200)
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

it ('only authorized users can create a new user', function () {
    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it ('only an admin or a manager can create a new user', function () {
    $userRole = Role::where('name', UserRole::USER)->first();

    $user = User::factory()->for(Role::where('name', $userRole->name)->first())->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'role_id' => $userRole->id,
    ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it ('only an admin can create a new admin user', function () {
    $managerRole = Role::where('name', UserRole::MANAGER)->first();
    $adminRole = Role::where('name', UserRole::ADMIN)->first();

    $user = User::factory()->for($managerRole)->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'role_id' => $adminRole->id,
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

it ('a user can\'t update another user', function () {
    $user = User::factory()->create(['role_id' => Role::where('name', UserRole::USER->value)->first()]);
    $anotherUser = User::factory()->create(['role_id' => Role::where('name', UserRole::USER->value)->first()]);

    actingAs($user, 'sanctum');

    $this->putJson('/api/users/' . $anotherUser->id, [
        'name' => fake()->name . '_updated',
    ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it ('a user can update himself', function () {
    $user = User::factory()->create();
    $originalName = $user->name;

    actingAs($user, 'sanctum');

    $response = $this->putJson('/api/users/' . $user->id, [
        'name' => fake()->name . '_updated',
    ])
        ->assertStatus(Response::HTTP_OK);

    $this->assertNotEquals($originalName, $response->content('name'));
});

it ('an admin can update any user', function () {
    $admin = User::factory()->create(['role_id' => Role::where('name', UserRole::ADMIN->value)->first()]);
    $user = User::factory()->create(['role_id' => Role::where('name', UserRole::USER->value)->first()]);

    actingAs($admin, 'sanctum');

    $this->putJson('/api/users/' . $user->id, [
        'name' => fake()->name . '_updated',
    ])
        ->assertStatus(Response::HTTP_OK);
});








