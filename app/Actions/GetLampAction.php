<?php

declare(strict_types=1);

namespace App\Actions;

use App\Commands\GetLampQuery;
use App\Exceptions\LampNotFoundException;
use App\Http\Responder;
use App\Resources\Lamp;
use EventSauce\EventSourcing\UuidAggregateRootId;
use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetLampAction
{
    private CommandBus $commandBus;
    private Responder $responder;

    // @todo inject dependencies in proper way
    public function __construct(ContainerInterface $container)
    {
        $this->commandBus = $container->get('command_bus');
        $this->responder = new Responder();
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = new UuidAggregateRootId($args['id']);
        $query = new GetLampQuery($id);
        try {
            /** @var \App\Models\Lamp $lamp */
            $lamp = $this->commandBus->handle($query);
        } catch (LampNotFoundException $exception) {
            throw new HttpNotFoundException($request, "Lamp with id {$exception->id()->toString()} not found");
        }

        $resource = new Lamp($lamp);
        return $this->responder->respond($response, $resource->toArray());
    }
}
