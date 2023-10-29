<?php

use function Pest\Laravel\withHeaders;

test('should not create a rating of establishment with invalid request schema', function() {

    $establishment = createEstablishment();

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->post('/api/v1/establishments/' . $establishment->id . '/ratings', [
        'price_stars' => 'https://facebook.com',
        'environment_stars' => 8
    ]);

    $reponse->assertStatus(422);
});

test('should create a rating of establishment', function() {

    $establishment = createEstablishment();

    $reponse = withHeaders([
        'accept' => 'application/json'
    ])->post('/api/v1/establishments/' . $establishment->id . '/ratings', [
        'price_stars' => 5,
        'environment_stars' => 4,
        'name' => 'John Smith',
        'birthday' => '1984-08-01'
    ]);

    $reponse->assertCreated();
    $reponse->assertSee('John Smith');
});
