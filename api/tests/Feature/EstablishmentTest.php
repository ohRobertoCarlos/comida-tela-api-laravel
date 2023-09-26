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
