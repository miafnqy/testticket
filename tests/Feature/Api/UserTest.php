<?php

use function Pest\Laravel\actingAs;
use \Symfony\Component\HttpFoundation\Response;

it('unauthorized users can\'t access /api/users', function () {
    $response = $this->get('/api/users', [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
});

it ('/api/users endpoint returns a users list', function () {
    $user = \App\Models\User::factory()->create();

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

it ('a user can create a new user', function () {
    $user = \App\Models\User::factory()->create();

    actingAs($user, 'sanctum');

    $this->postJson('/api/users', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
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








