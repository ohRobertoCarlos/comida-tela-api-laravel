<?php

namespace App\Establishments\Http\Controllers;

use App\Establishments\Http\Requests\CreateEstablishmentRequest;
use App\Establishments\Http\Requests\UpdateEstablishmentRequest;
use App\Establishments\Http\Requests\UserIsAdminRequest;
use App\Establishments\Http\Resources\Establishment;
use App\Establishments\Services\EstablishmentService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
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
            $establishment = $this->service->get($establishmentId);
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
            $establishment = $this->service->create($request->validated());
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
            $establishment = $this->service->update($establishmentId, $request->validated());
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
            $establishment = $this->service->delete($establishmentId);
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
}