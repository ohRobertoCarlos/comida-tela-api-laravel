<?php

use function Pest\Laravel\withHeaders;

test('non-logged user must not create an establishment category', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/categories', [
        'name' => 'Cakes'
    ]);

    $reponse->assertForbidden();
});

test('Admin user cannot create an establishment category', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $token = getTokenUserAdminLogged();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/categories', [
        'name' => 'Cakes 2'
    ]);

    $reponse->assertForbidden();
});

test('establishment user create can an establishment category', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/categories', [
        'name' => 'Cakes 2'
    ]);

    $reponse->assertCreated();
});

