<?php
use function \Pest\Laravel\{
    withHeaders,
};

test("must cannot view establishments", function () {
    $token = getTokenUserLogged();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/v1/establishments');

    $reponse->assertForbidden();
});

test('must can view establishments', function () {
    $token = getTokenUserAdminLogged();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/v1/establishments');

    $reponse->assertSuccessful();
});

test("must cannot create establishments", function () {
    $token = getTokenUserLogged();
    $establishment = makeEstablishment();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/v1/establishments', $establishment->toArray());

    $reponse->assertForbidden();
});

test("must create establishments", function () {
    $token = getTokenUserAdminLogged();
    $establishment = makeEstablishment();
    \Illuminate\Support\Facades\Storage::fake('test-disk');

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/v1/establishments', $establishment->toArray());

    $reponse->assertCreated();
});

test("must cannot update establishments", function () {
    $token = getTokenUserLogged();
    $establishment = createEstablishment();

    $establishment->name = 'Restaurant SA';

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->patchJson('/api/v1/establishments/' . $establishment->id, $establishment->toArray());

    $reponse->assertForbidden();
});

test("must update establishments", function () {
    $token = getTokenUserAdminLogged();
    $establishment = createEstablishment();

    $establishment->name = 'Restaurant SA';
    $establishment->description = fake()->text();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->patchJson('/api/v1/establishments/' . $establishment->id, $establishment->toArray());

    $reponse->assertOk();
});

test("must cannot delete establishments", function () {
    $token = getTokenUserLogged();
    $establishment = createEstablishment();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->delete('/api/v1/establishments/' . $establishment->id);

    $reponse->assertForbidden();
});

test("must delete establishments", function () {
    $token = getTokenUserAdminLogged();
    $establishment = createEstablishment();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->delete('/api/v1/establishments/' . $establishment->id);

    $reponse->assertOk();
});


test("must cannot list users of establishment", function () {
    $token = getTokenUserLogged();
    $establishment = createEstablishment();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->get('/api/v1/establishments/' . $establishment->id .'/users');

    $reponse->assertForbidden();
});

test("must list users of establishment", function () {
    $token = getTokenUserAdminLogged();
    $establishment = createEstablishment();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->get('/api/v1/establishments/' . $establishment->id .'/users');

    $reponse->assertOk();
});

test("must not create user of establishment", function () {
    $token = getTokenUserLogged();
    $establishment = createEstablishment();
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->make();
    $user->establishment_id = $establishment->id;

    $body = $user->toArray();
    $body['password'] = '$passwOrd494';
    $body['password_confirmation'] = '$passwOrd494';

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/establishments/' . $establishment->id .'/users', $body);

    $reponse->assertForbidden();
});

test("must create user of establishment", function () {
    $token = getTokenUserAdminLogged();
    $establishment = createEstablishment();
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->make();
    $user->establishment_id = $establishment->id;

    $body = $user->toArray();
    $body['password'] = '$passwOrd494';
    $body['password_confirmation'] = '$passwOrd494';

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/establishments/' . $establishment->id .'/users', $body);

    $reponse->assertCreated();
});
