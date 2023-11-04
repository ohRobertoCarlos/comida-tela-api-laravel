<?php

use function Pest\Laravel\withHeaders;

test('shoud return header http content-language containing all languages with support', function () {
    $response = withHeaders([
        'Content-Type' => 'application/json',
        'Accept-Language' => '*',
    ])->get('/api/v1/establishments/' . fake()->uuid() . '/menus/items');

    $response->assertHeader('Content-Language', implode(', ', config('app.available_locales')));
});

test('shoud return header http content-language containing determined language', function () {
    $response = withHeaders([
        'Content-Type' => 'application/json',
        'Accept-Language' => 'pt-BR',
    ])->get('/api/v1/establishments/' . fake()->uuid() . '/menus/items');

    $response->assertHeader('Content-Language', 'pt-BR');
});
