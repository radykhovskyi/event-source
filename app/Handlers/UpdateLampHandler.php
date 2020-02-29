<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\UpdateLampCommand;
use App\Exceptions\LampNotFoundException;
use App\Models\Lamp;
use EventSauce\EventSourcing\AggregateRootRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class UpdateLampHandler
{
    private AggregateRootRepository $repository;

    public function __construct(AggregateRootRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(UpdateLampCommand $command)
    {
        /** @var \App\Models\Lamp $lamp */
        $lamp = $this->repository->retrieve($command->id());
        if ($lamp->aggregateRootVersion() === 0) {
            throw new LampNotFoundException($command->id());
        }
        if ($command->state() === null && $command->location() === null) {
            return $lamp;
        }

        $lamp = $this->processUpdate($command, $lamp);
        $this->repository->persist($lamp);
        return $lamp;
    }

    private function processUpdate(UpdateLampCommand $command, Lamp $lamp): Lamp
    {
        if ($command->state() !== null) {
            if ($command->state() !== $lamp->state()) {
                $command->state() === Lamp::STATE_ON
                    ? $lamp->turnOn()
                    : $lamp->turnOff();
            }
        }

        if ($command->location() !== null) {
            if ($command->location() !== $lamp->location()) {
                $lamp->changeLocation($command->location());
            }
        }

        return $lamp;
    }
}
