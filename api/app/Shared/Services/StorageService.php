<?php

namespace App\Shared\Services;

use Illuminate\Support\Facades\Storage;

class StorageService
{
    public static function getUrlPublicFile(string $publicFilePath) : string
    {
        if (filter_var($publicFilePath, FILTER_VALIDATE_URL) !== false) {
            return $publicFilePath;
        }

        return Storage::disk(env('PUBLIC_FILESYSTEM_DISK'))->url($publicFilePath);
    }
}
