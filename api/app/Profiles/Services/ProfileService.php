<?php

namespace App\Profiles\Services;

use App\Profiles\Repositories\ProfileRepository;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProfileService
{
    public function __construct(
        private ProfileRepository $repository
    ){}

    public function update(string $establishmentId, array $data) : bool
    {
        $profile = $this->repository->getByEstablishmentId($establishmentId);
        if (empty($profile)) {
            throw new Exception('Profile not found');
        }

        if (isset($data['image_cover_profile_url'])) {
            if (!$this->validateFileUrl($data['image_cover_profile_url'])) {
                throw new Exception("Invalid cover image url");
            }

            $data['image_cover_profile_location'] = $data['image_cover_profile_url'];
        }

        if (isset($data['image_cover_background_profile_url'])) {
            if (!$this->validateFileUrl($data['image_cover_background_profile_url'])) {
                throw new Exception("Invalid cover background image url");
            }

            $data['image_cover_background_profile_location'] = $data['image_cover_background_profile_url'];
        }

        return $profile->update($data);
    }

    private function validateFileUrl(string $fileUrl)
    {
        return $this->isFileInPublicStorage($fileUrl);
    }

    private function isFileInPublicStorage(string $fileUrl): bool
    {
        $publicDiskName = env('PUBLIC_FILESYSTEM_DISK', 'public');

        try {
            $baseUrl = Storage::disk($publicDiskName)->url('');
            if (!\Illuminate\Support\Str::startsWith($fileUrl, $baseUrl)) {
                return false;
            }

            $relativePath = \Illuminate\Support\Str::after($fileUrl, $baseUrl);

            return Storage::disk($publicDiskName)->exists($relativePath);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function storageImageProfile(File|UploadedFile $image, string $establishmentId) : string
    {
        try {
            $path = Storage::disk(env('PUBLIC_FILESYSTEM_DISK', 'public'))->put('profiles/' . $establishmentId, $image);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
            throw new \Exception('Unable to save image');
        }

        return $path;
    }
}
