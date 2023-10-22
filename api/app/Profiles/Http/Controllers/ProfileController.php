<?php

namespace App\Profiles\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Profiles\Http\Requests\UpdateProfileRequest;
use App\Profiles\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProfileController extends BaseController
{
    public function __construct(
        private ProfileService $service
    )
    {}

    public function update(UpdateProfileRequest $request, string $establishmentId) : JsonResponse
    {
        try {
            $this->service->update($establishmentId, $request->validated());
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('profiles.cold_not_updated')
            ], 400);
        }

        return response()->json([
            'message' => __('profiles.updated_successfully')
        ]);
    }
}
