<?php

namespace App\Ratings\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Ratings\Http\Requests\StoreRatingRequest;
use App\Ratings\Http\Resources\Rating;
use App\Ratings\Services\RatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class RatingController extends BaseController
{
    public function __construct(
        private RatingService $service
    )
    {}

    /**
     * @unauthenticated
     */
    public function store(StoreRatingRequest $request, string $establishmentId) : JsonResponse|Rating
    {
        try {
            $rating = $this->service->store(data: $request->validated(), establishmentId: $establishmentId);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => __('ratings.cold_not_create'),
            ], 400);
        }

        return new Rating($rating);
    }
}
