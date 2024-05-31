<?php

namespace App\Shared\Services;

use Illuminate\Support\Facades\Storage;

class StorageService
{
    public static function getUrlPublicFile(string $publicFilePath) : string
    {
        return Storage::disk(env('PUBLIC_FILESYSTEM_DISK'))->url($publicFilePath);
    }
}
