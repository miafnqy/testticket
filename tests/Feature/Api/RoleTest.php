<?php

use \Symfony\Component\HttpFoundation\Response;
use \App\Models\User;
use function Pest\Laravel\actingAs;

it('unauthorized users can\'t access /api/roles', function () {
    $this->getJson('/api/roles')
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('/api/roles returns a list of roles', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $this->getJson('/api/roles')
        ->assertStatus(Response::HTTP_OK);
});
