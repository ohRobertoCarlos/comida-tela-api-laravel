<?php

namespace App\Establishments\Http\Controllers;

use App\Auth\Http\Resources\User;
use App\Establishments\Http\Requests\CreateEstablishmentRequest;
use App\Establishments\Http\Requests\CreateUserRequest;
use App\Establishments\Http\Requests\UpdateEstablishmentRequest;
use App\Establishments\Http\Requests\UpdateUserRequest;
use App\Establishments\Http\Requests\UserIsAdminRequest;
use App\Establishments\Http\Resources\Establishment;
use App\Establishments\Services\EstablishmentService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;
use Throwable;

class EstablishmentController extends BaseController
{
    public function __construct(
        private EstablishmentService $service
    )
    {}

    public function index(UserIsAdminRequest $request) : ResourceCollection
    {
        return Establishment::collection($this->service->getAll());
    }

    public function show(UserIsAdminRequest $request, $establishmentId) : Establishment|JsonResponse
    {
        try {
            $establishment = $this->service->get(id: $establishmentId);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (!isset($establishment) || empty($establishment)) {
            return response()->json([
                'message' => __('establishments.not_show_establishment')
            ], 404);
        }

        return new Establishment($establishment);
    }

    public function store(CreateEstablishmentRequest $request) : Establishment|JsonResponse
    {
        try {
            $establishment = $this->service->create(data: $request->validated());
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (!isset($establishment) ||empty($establishment)) {
            return response()->json([
                'message' => __('establishments.not_create_establishment')
            ], 400);
        }

        return new Establishment($establishment);
    }

    public function update(UpdateEstablishmentRequest $request, $establishmentId) : JsonResponse
    {
        try {
            $establishment = $this->service->update(id: $establishmentId, data: $request->validated());
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (!isset($establishment) || !$establishment) {
            return response()->json([
                'message' => __('establishments.not_update_establishment')
            ], 400);
        }

        return response()->json([
            'message' => __('establishments.success_update_establishment')
        ], 200);
    }

    public function destroy(UserIsAdminRequest $request, $establishmentId)
    {
        try {
            $establishment = $this->service->delete(id: $establishmentId);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (!isset($establishment) || !$establishment) {
            return response()->json([
                'message' => __('establishments.not_delete_establishment')
            ], 400);
        }

        return response()->json([
            'message' => __('establishments.success_delete_establishment')
        ], 200);
    }

    public function createUser(CreateUserRequest $request, string $establishmentId) : User|JsonResponse
    {
        try {
            $user = $this->service->createUser(establishmentId: $establishmentId, data: $request->validated());
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (empty($user)) {
            return response()->json([
                'message' => __('establishments.cold_not_create_user')
            ], 400);
        }

        return new User($user);
    }

    public function getUsers(UserIsAdminRequest $request, string $establishmentId) : AnonymousResourceCollection
    {
        $users = $this->service->getUsers(establishmentId: $establishmentId);

        return User::collection($users);
    }

    public function updateUser(UpdateUserRequest $request, string $establishmentId, $userId) : JsonResponse
    {
        try {
            $userUpdated = $this->service->updateUser(establishmentId: $establishmentId, userId: $userId, data: $request->validated());
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (empty($userUpdated) || !$userUpdated) {
            return response()->json([
                'message' => __('establishments.cold_not_update_user')
            ], 400);
        }

        return response()->json([
            'message' => __('establishments.user_updated')
        ], 200);
    }

    public function deleteUser(UserIsAdminRequest $request, string $establishmentId, $userId) : JsonResponse
    {
        try {
            $userDeleted = $this->service->deleteUser(establishmentId: $establishmentId, userId: $userId);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (empty($userDeleted) || !$userDeleted) {
            return response()->json([
                'message' => __('establishments.cold_not_delete_user')
            ], 400);
        }

        return response()->json([
            'message' => __('establishments.user_deleted')
        ], 200);
    }

    public function getUser(UserIsAdminRequest $request, string $establishmentId, $userId) : User|JsonResponse
    {
        try {
            $user = $this->service->getUser(establishmentId: $establishmentId, userId: $userId);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (empty($user)) {
            return response()->json([
                'message' => __('establishments.user_establishment_not_found')
            ], 400);
        }

        return new User($user);
    }

    /**
     * @unauthenticated
     */
    public function showByMenuCode($menuCode) : Establishment|JsonResponse
    {
        try {
            $establishment = $this->service->getByMenuCode(menuCode: $menuCode);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (!isset($establishment) || empty($establishment)) {
            return response()->json([
                'message' => __('establishments.not_show_establishment')
            ], 404);
        }

        return new Establishment($establishment);
    }
}
