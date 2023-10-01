<?php

namespace App\Establishments\Services;

use App\Contracts\Repository;
use App\Establishments\Repositories\EstablishmentRepository;
use App\Models\BaseModel;
use chillerlan\QRCode\QRCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EstablishmentService
{
    public function __construct(
        private Repository $repository = new EstablishmentRepository()
    )
    {}

    public function getAll() : Collection
    {
        return $this->repository->all();
    }

    public function get(string $id) : BaseModel
    {
        return $this->repository->findById($id);
    }

    public function create(array $data) : BaseModel|null
    {
        $data['menu_code'] = Str::slug($data['name'] ?? '', '-');

        DB::beginTransaction();
        try {
            $establishment = $this->repository->create($data);
            $this->createMenu(establishment: $establishment);
            $this->createProfile(establishment: $establishment);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $establishment;
    }

    public function update(string $id, array $data) : bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string $id) : bool
    {
        return $this->repository->delete($id);
    }

    public function createMenu(BaseModel $establishment) : void
    {
        $qrCodePath = $this->generateQrCodeMenu(establishment: $establishment);
        $this->repository->createMenu(establishment: $establishment, qrCodeImagePath: $qrCodePath);
    }

    public function createProfile(BaseModel $establishment) : void
    {
        $this->repository->createProfile($establishment);
    }

    public function generateQrCodeMenu(BaseModel $establishment) : string
    {
        $base64 = (new QRCode())->render(
            env('APP_CLIENT_URL', 'http://localhost:3000') . '/' . $establishment->menu_code
        );
        $base64 = str_replace('data:image/png;base64,', '', $base64);
        $base64 = str_replace(' ', '+', $base64);

        $path = 'menus/qrcodes/' . $establishment->id . '.png';
        if (!Storage::put($path, base64_decode($base64))) {
            throw new \Exception('Unable to save file');
        }

        return $path;
    }

    public function createUser(string $establishmentId, array $data) : BaseModel
    {
        $user = $this->repository->createUser(establishmentId: $establishmentId, data: $data);

        $user->sendWelcomeEmail();

        return $user;
    }

    public function getUsers(string $establishmentId)
    {
        return $this->repository->getUsers(establishmentId: $establishmentId);
    }

    public function updateUser(string $establishmentId, $userId, array $data) : bool
    {
        return $this->repository->updateUser(establishmentId: $establishmentId, userId: $userId, data: $data);
    }

    public function deleteUser(string $establishmentId, $userId) : bool
    {
        return $this->repository->deleteUser(establishmentId: $establishmentId, userId: $userId);
    }
}
