<?php

use function \Pest\Laravel\{
    withHeaders,
};

test('should not show a menu of establisment', function() {
    $token = getTokenUserAdminLogged();
    $establishment = makeEstablishment();
    $establishment->id = fake()->uuid();
    \Illuminate\Support\Facades\Storage::fake('test-disk-public');

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/v1/establishments', $establishment->toArray());

    $reponse->assertCreated();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->get('/api/v1/establishments/' . $establishment->id . '/menus');

    $reponse->assertForbidden();
});

test('should show a menu of establisment', function() {
    $token = getTokenUserAdminLogged();
    $establishment = makeEstablishment();
    $establishment->id = fake()->uuid();
    \Illuminate\Support\Facades\Storage::fake('test-disk-public');

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/v1/establishments', $establishment->toArray());

    $reponse->assertCreated();

    $token = getTokenUserEstablishmentLogged($establishment->id);
    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->get('/api/v1/establishments/' . $establishment->id . '/menus');

    $reponse->assertJsonFragment([
        'establishment_id' => $establishment->id
    ]);
});
