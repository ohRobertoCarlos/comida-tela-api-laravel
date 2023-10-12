<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\TestResponse;

use function Pest\Laravel\withHeaders;

uses(\Tests\TestCase::class)->in('Feature');

function getTokenUserLogged() : string
{
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create();

    return auth()->attempt([
        'email' => $user->email,
        'password' => 'password'
    ]);
}

function getTokenUserAdminLogged() : string
{
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['is_admin' => true]);

    return auth()->attempt([
        'email' => $user->email,
        'password' => 'password'
    ]);
}

function makeEstablishment() : \App\Models\BaseModel
{
    return (new \App\Establishments\Repositories\EstablishmentRepository())
        ->getModel()
        ->factory()
        ->make();
}

function createEstablishment() : \App\Models\BaseModel
{
    return (new \App\Establishments\Repositories\EstablishmentRepository())
        ->getModel()
        ->factory()
        ->create();
}

function createUser() : \App\Models\User
{
    $repository = new \App\Auth\Repositories\UserRepository();
    return $repository->getModel()->factory()->create();
}

function buildWelcomeEmail($user)
{
    $token = \Illuminate\Support\Facades\Password::createToken($user);
    $resetUrl = env('APP_CLIENT_URL') . '/reset-password?token='.$token;

    return new \App\Auth\Mail\Welcome($user, $resetUrl);
}

function buildVerifyEmail($user)
{
    return new \App\Auth\Mail\VerifyEmail($user, buildUrlVerifyEmail($user));
}

function buildUrlVerifyEmail($user)
{
    return URL::temporarySignedRoute(
        'verification.verify',
        Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
        [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]
    );
}

function getTokenUserEstablishmentLogged(string $establishmentId) : string
{
    $repository = new \App\Auth\Repositories\UserRepository();
    $user = $repository->getModel()->factory()->create(['establishment_id' => $establishmentId]);

    return auth()->attempt([
        'email' => $user->email,
        'password' => 'password'
    ]);
}

function makeItemMenu(string $menuId) : \App\Models\BaseModel
{
    return (new App\Items\Repositories\ItemRepository())
        ->getModel()
        ->factory()
        ->make(['menu_id' => $menuId]);
}

function createItemMenu(array $data) : \App\Models\BaseModel
{
    return (new App\Items\Repositories\ItemRepository())
        ->getModel()
        ->factory()
        ->create($data);
}

function createEstablishmentWithMenu() : TestResponse
{
    $token = getTokenUserAdminLogged();
    $establishment = makeEstablishment();
    $establishment->id = fake()->uuid();
    \Illuminate\Support\Facades\Storage::fake('test-disk-public');

    return withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->postJson('/api/v1/establishments', $establishment->toArray());
}
