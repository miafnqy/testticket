<?php

use Laravel\Sanctum\Sanctum;

it('a user can\'t be logged in with invalid credentials', function () {

    $user = \App\Models\User::factory()->create();

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
    $user = \App\Models\User::factory()->create();

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
    $user = \App\Models\User::factory()->create();

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





