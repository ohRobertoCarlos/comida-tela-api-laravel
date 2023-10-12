<?php

use Illuminate\Http\UploadedFile;

use function \Pest\Laravel\{
    withHeaders,
};

test('should not create a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();
    $token = fake()->uuid();
    $item = makeItemMenu($reponse->json('data.menu.id'));

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menu/items', $item->toArray());

    $reponse->assertForbidden();
});


test('should create a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = makeItemMenu($reponse->json('data.menu.id'));
    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));
    $data = $item->toArray();
    $data['cover_image'] = UploadedFile::fake()->image('photo1.jpg');

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menu/items', $data);

    $reponse->assertCreated();
});

test('should not update a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);
    $token = fake()->uuid();

    $data = ['title' => 'Farofa', 'portions' => 5];

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/menu/items/' . $item->id, $data);

    $reponse->assertForbidden();
});


test('should update a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);
    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));

    $data = ['title' => 'Farofa', 'portions' => 5];

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/menu/items/' . $item->id, $data);

    $reponse->assertOk();
});


test('should not delete a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);
    $token = fake()->uuid();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->delete('/api/v1/establishments/' . $reponse->json('data.id') . '/menu/items/' . $item->id);

    $reponse->assertForbidden();
});


test('should delete a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);
    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->delete('/api/v1/establishments/' . $reponse->json('data.id') . '/menu/items/' . $item->id);

    $reponse->assertOk();
});
