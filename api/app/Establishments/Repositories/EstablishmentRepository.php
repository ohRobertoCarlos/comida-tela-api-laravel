<?php

namespace App\Establishments\Repositories;

use App\Auth\Repositories\UserRepository;
use App\Establishments\Models\Establishment;
use App\Menus\Repositories\MenuRepository;
use App\Models\BaseModel;
use App\Profiles\Repositories\ProfileRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class EstablishmentRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Establishment();
    }

    public function createMenu(BaseModel $establishment, string $qrCodeImagePath) : void
    {
        if (empty($establishment)) {
            throw new \InvalidArgumentException('Establishment not informed');
        }

        $this->getMenuRepository()->create([
            'establishment_id' => $establishment->id,
            'qr_code_image_path' => $qrCodeImagePath
        ]);
    }

    public function createProfile(BaseModel $establishment) : void
    {
        if (empty($establishment)) {
            throw new \InvalidArgumentException('Establishment not informed');
        }

        $this->getProfileRepository()->create([
            'establishment_id' => $establishment->id,
        ]);
    }

    private function getMenuRepository() : BaseRepository
    {
        return new MenuRepository();
    }

    private function getProfileRepository() : BaseRepository
    {
        return new ProfileRepository();
    }

    public function createUser(string $establishmentId, array $data)
    {
        try {
            $establishment = $this->findById($establishmentId);

            if (empty($establishment)) {
                throw new \Exception('Establishment not found');
            }
        } catch (\Throwable $e) {
            throw $e;
        }

        $data['establishment_id'] = $establishment->id;

        return $this->getUserRepository()->create(data: $data);
    }

    private function getUserRepository() : BaseRepository|UserRepository
    {
        return new UserRepository();
    }

    public function getUsers(string $establishmentId) : Collection
    {
        return $this->getUserRepository()->getAllByEstablishmentId(establishmentId: $establishmentId);
    }

    public function updateUser(string $establishmentId, $userId, array $data) : bool
    {
        $user = $this->getUser(establishmentId: $establishmentId, userId: $userId);

        return $this->getUserRepository()->update(id: $user->id, data: $data);
    }

    public function deleteUser(string $establishmentId, $userId) : bool
    {
        $user = $this->getUser(establishmentId: $establishmentId, userId: $userId);

        return $this->getUserRepository()->delete(id: $user->id);
    }


    public function getUser(string $establishmentId, $userId) : BaseModel
    {
        try {
            $establishment = $this->findById($establishmentId);
            if (empty($establishment)) {
                throw new \Exception('Establishment not found');
            }

            $user = $this->getUserRepository()->findById($userId);
            if (empty($user) || $user->establishment_id !== $establishment->id) {
                throw new \Exception('User not found');
            }

        } catch (\Throwable $e) {
            throw $e;
        }

        return $user;
    }

    public function getByMenuCode(string $menuCode) : BaseModel|null
    {
        return $this->getModel()
            ->where('menu_code', $menuCode)
            ->with(['menu', 'profile'])
            ->first();
    }
}
