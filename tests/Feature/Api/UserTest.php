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

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
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

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
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
    $userRole = Role::find(UserRole::USER);

    $user = User::factory()->for($userRole)->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'role_id' => $userRole->id,
    ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it ('only an admin can create a new admin user', function () {
    $managerRole = Role::find(UserRole::MANAGER);

    $user = User::factory()->for($managerRole)->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'role_id' => UserRole::ADMIN,
    ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it ('a user can create a new user', function () {
    $user = User::factory()->for(Role::find(UserRole::ADMIN))->create();

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
    $user = User::factory()->create(['role_id' => UserRole::USER]);
    $anotherUser = User::factory()->create(['role_id' => UserRole::USER]);

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
    $admin = User::factory()->create(['role_id' => UserRole::ADMIN]);
    $user = User::factory()->create(['role_id' => UserRole::USER]);

    actingAs($admin, 'sanctum');

    $this->putJson('/api/users/' . $user->id, [
        'name' => fake()->name . '_updated',
    ])
        ->assertStatus(Response::HTTP_OK);
});

it ('unauthorized user can\'t delete a user', function () {
    $this->deleteJson('/api/users/' . 1)
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it ('a user can\'t delete another user', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    actingAs($user, 'sanctum');

    $this->deleteJson('/api/users/' . $anotherUser->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it ('a user can delete a himself', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');
    $this->deleteJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it ('an admin can delete any user', function () {
    $admin = User::factory()->create(['role_id' => UserRole::ADMIN]);
    $manager = User::factory()->create(['role_id' => UserRole::MANAGER]);

    actingAs($admin, 'sanctum');

    $this->deleteJson('/api/users/' . $manager->id)
        ->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseMissing('users', ['id' => $manager->id]);
});








