<?php

use function \Pest\Laravel\{
    withHeaders,
};

test("Admin should request verification email on unverified user ", function () {
    $token = getTokenUserAdminLogged();
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['email_verified_at' => null]);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/auth/email/verification-notification/' . $user->id);

    $reponse->assertOk();
});

test("Admin should not request verification email on verified user ", function () {
    $token = getTokenUserAdminLogged();
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['email_verified_at' => now()->subMinute()]);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/auth/email/verification-notification/' . $user->id);

    $reponse->assertBadRequest();
});

test("User non-admin should not request verification email on unverified user ", function () {
    $token = getTokenUserLogged();
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['email_verified_at' => null]);

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/auth/email/verification-notification/' . $user->id);

    $reponse->assertForbidden();
});

test("Unverified logged user must request verification email", function () {
    $token = getTokenUserLogged();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->post('/api/v1/auth/email/verification-notification');

    $reponse->assertOk();
});

test("Non-logged user should not request verification email", function () {
    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer dadadadawdd454d4wad8344dawdawddwdsads'
    ])->post('/api/v1/auth/email/verification-notification');

    $reponse->assertUnauthorized();
});

test("User should verify email", function () {
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['email_verified_at' => null]);
    $url = buildUrlVerifyEmail($user);

    $reponse = withHeaders([
        'accept' => 'application/json',
    ])->get($url);

    $reponse->assertRedirect(env('APP_CLIENT_URL') . '/email/verify/success');
});

test("User should not verify email", function () {
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['email_verified_at' => now()->subMinute()]);
    $url = buildUrlVerifyEmail($user);

    $reponse = withHeaders([
        'accept' => 'application/json',
    ])->get($url);

    $reponse->assertRedirect(env('APP_CLIENT_URL') . '/email/verify/already-success');
});

test("Url invalid error verification email", function () {
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['email_verified_at' => null]);
    $url = buildUrlVerifyEmail($user);

    $url = $url . 'r';

    $reponse = withHeaders([
        'accept' => 'application/json',
    ])->get($url);

    $reponse->assertRedirect(env('APP_CLIENT_URL') . '/email/verify/error');
});
