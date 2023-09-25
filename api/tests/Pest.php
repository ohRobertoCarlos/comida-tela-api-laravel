<?php

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
