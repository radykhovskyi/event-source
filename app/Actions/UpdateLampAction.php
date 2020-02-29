<?php

declare(strict_types=1);

namespace App\Actions;

use App\Commands\UpdateLampCommand;
use App\Exceptions\LampNotFoundException;
use App\Http\Responder;
use App\Models\Lamp;
use App\Resources\Lamp as LampResource;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\UuidAggregateRootId;
use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class UpdateLampAction
{
    private AggregateRootRepository $repository;
    private CommandBus $commandBus;
    private Responder $responder;

    // @todo inject dependencies in proper way
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get('repository');
        $this->commandBus = $container->get('command_bus');
        $this->responder = new Responder();
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $state = $request->getParsedBody()['state'] ?? null;
        $location = $request->getParsedBody()['location'] ?? null;
        $command = new UpdateLampCommand(new UuidAggregateRootId($id), $state, $location);

        try {
            $lamp = $this->commandBus->handle($command);
        } catch (LampNotFoundException $exception) {
            throw new HttpNotFoundException($request, "Lamp with id {$exception->id()->toString()} not found");
        }

        $resource = new LampResource($lamp);
        return $this->responder->respond($response, $resource->toArray());
    }
}
