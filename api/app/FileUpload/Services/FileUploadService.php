<?php

namespace App\FileUpload\Services;

use App\Shared\Services\StorageService;
use DateTime;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileUploadService
{
    public function storePublicFile(File|UploadedFile $file) : string
    {
        try {
            $now = new DateTime();
            $year = $now->format('Y');
            $month = $now->format('m');
            $day = $now->format('d');

            $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

            $path = "uploads/{$year}/{$month}/{$day}/{$uuid}";

            $path = Storage::disk(env('PUBLIC_FILESYSTEM_DISK', 'public'))->put($path, $file);

        } catch(Throwable $e) {
            Log::error($e->getMessage());
            throw new \Exception('Unable to save image');
        }

        return StorageService::getUrlPublicFile($path);
    }
}
