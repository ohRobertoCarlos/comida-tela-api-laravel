<?php

namespace App\Ratings\Services;

use App\Establishments\Services\EstablishmentService;
use App\Models\BaseModel;
use App\Ratings\Repositories\RatingRepository;
use Exception;

class RatingService
{
    public function __construct(
        private RatingRepository $repository,
        private EstablishmentService $establishmentService
    )
    {}

    public function store(array $data, string $establishmentId) : BaseModel
    {
        if (
            empty($this->establishmentService->get($establishmentId))
        ) {
            throw new Exception('Establishment not found.');
        }

        $data['establishment_id'] = $establishmentId;
        return $this->repository->create($data);
    }
}
