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

test('Admin user cannot delete an establishment category', function () {
    $establishment = createEstablishment();
    $token = getTokenUserAdminLogged();

    $category = createCategoryEstablishment($establishment->id);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->delete('/api/v1/establishments/' . $establishment->id . '/categories/' . $category->id);

    $reponse->assertForbidden();
});

test('establishment user must delete an establishment category', function () {
    $establishment = createEstablishment();
    $token = getTokenUserEstablishmentLogged($establishment->id);

    $category = createCategoryEstablishment($establishment->id);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->delete('/api/v1/establishments/' . $establishment->id . '/categories/' . $category->id);

    $reponse->assertOk();

    $category = $category->fresh();

    $this->assertTrue(!empty($category->deleted_at));
});

test('establishment user must not delete an establishment category with items', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));

    $category = createCategoryEstablishment($reponse->json('data.id'));
    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $item->categories()->sync([$category->id]);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->delete('/api/v1/establishments/' . $reponse->json('data.id') . '/categories/' . $category->id);

    $reponse->assertBadRequest();
});
