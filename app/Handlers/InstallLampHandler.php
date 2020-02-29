<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\InstallLampCommand;
use App\Models\Lamp;
use EventSauce\EventSourcing\AggregateRootRepository;

class InstallLampHandler
{
    private AggregateRootRepository $repository;

    public function __construct(AggregateRootRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(InstallLampCommand $command)
    {
        $lamp = Lamp::install($command->location());
        $this->repository->persist($lamp);
        return $lamp;
    }
}
