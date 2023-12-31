<?php

use App\Categories\Enums\Category;
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
    $this->seed();

    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = makeItemMenu($reponse->json('data.menu.id'));
    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));
    $data = $item->toArray();
    $data['cover_image'] = UploadedFile::fake()->image('photo1.jpg');
    $data['categories'] = [Category::Highlights->value];

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items', $data);

    $reponse->assertCreated();

    $assert = count($reponse->json('data.categories')) === 1;

    expect($assert)->toBeTrue();
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
    $this->seed();

    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $item = createItemMenu(['menu_id' => $reponse->json('data.menu.id')]);
    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));

    $data = ['title' => 'Farofa', 'portions' => 5, 'categories' => [Category::Combos->value, Category::Highlights->value]];

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items/' . $item->id, $data);

    $item = $item->fresh();

    expect($item->categories->count())->toEqual(2);

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

    expect($assert)->toBeTrue();
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

    expect($assert)->toBeTrue();
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

    expect($item->likes)->toEqual(0);
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

    expect($item->likes)->toEqual(1);
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

    expect($item->not_likes)->toEqual(0);
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

    expect($item->not_likes)->toEqual(1);
});

test('should not search items in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    createItemMenu(['menu_id' => $reponse->json('data.menu.id'), 'title' => 'Cake', 'description' => 'Delicios Cake']);
    createItemMenu(['menu_id' => $reponse->json('data.menu.id'), 'title' => 'Orange juice']);

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->get('/api/v1/establishments/' . $reponse->json('data.id') . '/menus/items?title=burger');

    $assert = count($reponse->json('data')) === 0;

    expect($assert)->toBeTrue();
});

test('should search items in menu', function () {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    createItemMenu(['menu_id' => $reponse->json('data.menu.id'), 'title' => 'Cake', 'description' => 'Delicious cake']);
    createItemMenu(['menu_id' => $reponse->json('data.menu.id'), 'title' => 'Orange juice']);

    $establishmentId = $reponse->json('data.id');

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->get('/api/v1/establishments/' . $establishmentId . '/menus/items?title=Orange');

    $reponse->assertOk();
    $assert = count($reponse->json('data')) > 0;

    expect($assert)->toBeTrue();

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->get('/api/v1/establishments/' . $establishmentId . '/menus/items?title=Cak&description=cake');

    $reponse->assertOk();
    $assert = count($reponse->json('data')) > 0;

    expect($assert)->toBeTrue();

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->get('/api/v1/establishments/' . $establishmentId . '/menus/items?description=cakes');

    $reponse->assertOk();
    $assert = count($reponse->json('data')) === 0;

    expect($assert)->toBeTrue();
});
