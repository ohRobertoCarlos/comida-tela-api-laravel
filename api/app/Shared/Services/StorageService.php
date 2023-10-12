<?php

namespace App\Shared\Services;

class StorageService
{
    public static function getUrlPublicFile(string $publicFilePath) : string
    {
        return asset('storage/' . $publicFilePath);
    }
}
