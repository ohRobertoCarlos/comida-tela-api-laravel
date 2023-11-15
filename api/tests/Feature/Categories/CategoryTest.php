<?php

use function Pest\Laravel\withHeaders;

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

test('Admin user cannot update an establishment category', function () {
    $establishment = createEstablishment();
    $token = getTokenUserAdminLogged();

    $category = createCategoryEstablishment($establishment->id);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->patch('/api/v1/establishments/' . $establishment->id . '/categories/' . $category->id, [
        'name' => 'Orange Cakes'
    ]);

    $reponse->assertForbidden();
});

test('establishment user must update an establishment category', function () {
    $establishment = createEstablishment();
    $token = getTokenUserEstablishmentLogged($establishment->id);

    $category = createCategoryEstablishment($establishment->id);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->patch('/api/v1/establishments/' . $establishment->id . '/categories/' . $category->id, [
        'name' => 'Orange Cakes'
    ]);

    $reponse->assertOk();

    $category = $category->fresh();

    $this->assertEquals('Orange Cakes', $category->name);
});

