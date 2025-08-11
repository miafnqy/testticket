<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use \App\Models\User;

uses(RefreshDatabase::class);

it('a user can be signed up', function () {
    $response = $this->postJson('/api/signup', [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
            'auth' => [
                'token',
            ],
        ]);
    $this->assertDatabaseHas('users', [
        'id' => $response['data']['id'],
        'name' => $response['data']['name'],
        'email' => $response['data']['email'],
    ]);
});

it('a user can\'t be logged in with invalid credentials', function () {

    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'invalid-password',
    ]);

    $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'error' => 'Invalid Credentials',
        ]);
});

it('a user can be logged in', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => ['token']
        ]);
});

it ('a user can be logged out', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $token = $response->json('data')['token'];

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);

    $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->postJson('/api/logout')
        ->assertStatus(200);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);
});





