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

        if (!empty($data['image_cover_profile'])) {
            $data['image_cover_profile_location'] = $this->storageImageProfile($data['image_cover_profile'], $establishmentId);
        }

        return $profile->update($data);
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
