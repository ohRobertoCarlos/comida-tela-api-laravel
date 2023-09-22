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
    $establishment = (new \App\Establishments\Repositories\EstablishmentRepository())
        ->getModel()
        ->factory()
        ->make();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/v1/establishments', $establishment->toArray());

    $reponse->assertForbidden();
});
