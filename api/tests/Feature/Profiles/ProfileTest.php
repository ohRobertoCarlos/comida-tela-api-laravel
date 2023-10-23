<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\withHeaders;

test('should not update profile of establishment', function() {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer dadasdadewdladjakdhaduhg',
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/profiles', ['facebook_link' => 'https://facebook.com']);

    $reponse->assertForbidden();
});


test('user admin should not update profile of establishment', function() {
    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $token = getTokenUserAdminLogged();

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => $token,
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/profiles', ['facebook_link' => 'https://facebook.com']);

    $reponse->assertForbidden();
});


test('user should update profile of establishment', function() {
    Storage::fake('test-disk-public');

    $reponse = createEstablishmentWithMenu();
    $reponse->assertCreated();

    $token = getTokenUserEstablishmentLogged($reponse->json('data.id'));
    $file = UploadedFile::fake()->image('photo1.jpg');

    $reponse = withHeaders([
        'accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
    ])->patch('/api/v1/establishments/' . $reponse->json('data.id') . '/profiles', [
        'facebook_link' => 'https://facebook.com',
        'image_cover_profile' => $file,
        'opening_hours' => json_encode([
            'monday' => '12-21',
            'tuesday' => '12-21',
            'wednesday' => '12-21',
            'thursday' => '12-21',
            'friday' => '12-21',
            'saturday' => '12-21'
        ]),
        'address' => 'Rue de la ville',
        'payment_methods' => json_encode([
            'credit_card',
            'paypal',
        ])
    ]);

    $reponse->assertSuccessful();
});
