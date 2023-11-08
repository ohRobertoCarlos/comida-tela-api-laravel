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
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items', $item->toArray());

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
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items', $data);

    $reponse->assertCreated();
});

test('should not show a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);
    $id = fake()->uuid();

    $reponse = withHeaders([
        'accept' => 'application/json',
    ])->get('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $id);

    $reponse->assertNotFound();
});

test('should show a item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $reponse = withHeaders([
        'accept' => 'application/json',
    ])->get('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id);

    $reponse->assertJsonFragment(['id' => $item->id]);
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
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id, $data);

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
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id, $data);

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
    ])->delete('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id);

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
    ])->delete('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id);

    $reponse->assertOk();
});

test('should not list items in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->get('/api/v1/establishments/' . $reponse->json('data.id') . 'x' . '/menus/items');

    $assert = count($reponse->json('data')) === 0;
    $this->assertTrue($assert);
});

test('should list items in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);
    createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->get('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items');

    $assert = count($reponse->json('data')) > 0;
    $this->assertTrue($assert);
});

test('should not like item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $body = makeBodyRequestLikeUnlikeItem();
    unset($body['name']);

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id . '/like', $body);

    $reponse->assertUnprocessable();

    $item = $item->fresh();

    $this->assertEquals(0, $item->likes);
});

test('should like item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id . '/like', makeBodyRequestLikeUnlikeItem());

    $reponse->assertOk();

    $item = $item->fresh();

    $this->assertEquals(1, $item->likes);
});

test('should not unlike item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $body = makeBodyRequestLikeUnlikeItem();
    unset($body['name']);

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id . '/unlike', $body);

    $reponse->assertUnprocessable();

    $item = $item->fresh();

    $this->assertEquals(0, $item->not_likes);
});

test('should unlike item in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id . '/unlike', makeBodyRequestLikeUnlikeItem());

    $reponse->assertOk();

    $item = $item->fresh();

    $this->assertEquals(1, $item->not_likes);
});
