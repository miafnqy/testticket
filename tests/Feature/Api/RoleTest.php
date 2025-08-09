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

it('unauthorized users can\'t access /api/roles/{id}', function () {
    $this->getJson('/api/roles/' . UserRole::USER->value)
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

it('/api/roles/{id} returns a single role', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    actingAs($user, 'sanctum');

    $this->getJson('/api/roles/' . $role->id)
        ->assertStatus(Response::HTTP_OK)
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

    $response = $this->postJson('/api/roles', [
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
    $this->assertDatabaseHas('roles', ['id' => $response['data']['id']]);
});

it('users can\'t update user roles', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $this->putJson('/api/roles/' . UserRole::USER->value, [])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('managers can\'t update user roles', function () {
    $user = User::factory()->for(Role::find(UserRole::MANAGER))->create();

    actingAs($user, 'sanctum');

    $this->putJson('/api/roles/' . UserRole::USER->value, [])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('admins can update user roles', function () {
    $admin = User::factory()->for(Role::find(UserRole::ADMIN))->create();
    $role = Role::factory()->create();

    $newRoleName = fake()->unique()->jobTitle() . '_updated';

    actingAs($admin, 'sanctum');

    $response = $this->putJson('/api/roles/' . $role->id, [
        'name' => $newRoleName,
        'priority' => fake()->numberBetween(1,9),
    ])
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                'id' => $role->id,
                'name' => $newRoleName
            ]
        ]);
    $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => $newRoleName]);
});

it('unauthorized users can\'t delete user roles', function () {
    $this->deleteJson('/api/roles/' . UserRole::USER->value)
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('non admins can\'t delete user roles', function () {
    $manager = User::factory()->for(Role::find(UserRole::MANAGER))->create();

    actingAs($manager, 'sanctum');

    $this->deleteJson('/api/roles/' . UserRole::USER->value)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('admins can delete user roles', function () {
    $admin = User::factory()->for(Role::find(UserRole::ADMIN))->create();
    $role = Role::factory()->create();

    actingAs($admin, 'sanctum');

    $this->deleteJson('/api/roles/' . $role->id)
        ->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseMissing('roles', ['id' => $role->id]);
});
