<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\GetLampQuery;
use App\Exceptions\LampNotFoundException;
use EventSauce\EventSourcing\AggregateRootRepository;

class GetLampHandler
{
    private AggregateRootRepository $repository;

    public function __construct(AggregateRootRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetLampQuery $query)
    {
        $lamp = $this->repository->retrieve($query->id());
        if ($lamp->aggregateRootVersion() === 0) {
            throw new LampNotFoundException($query->id());
        }
        return $lamp;
    }
}
