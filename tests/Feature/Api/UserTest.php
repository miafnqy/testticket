<?php

it('unauthorized users can\'t access /api/users', function () {
    $response = $this->get('/api/users', [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
});

it ('/api/users endpoint returns a users list', function () {
    $user = \App\Models\User::factory()->create();

    \Pest\Laravel\actingAs($user, 'sanctum');

    $response = $this->get('/api/users')
        ->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ]
        ]);
});
