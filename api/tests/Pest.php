<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
