<?php

declare(strict_types=1);

namespace App\Actions;

use App\Commands\InstallLampCommand;
use App\Http\Responder;
use App\Resources\Lamp;
use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateLampAction
{
    private CommandBus $commandBus;
    private Responder $responder;

    // @todo inject dependencies in proper way
    public function __construct(ContainerInterface $container)
    {
        $this->commandBus = $container->get('command_bus');
        $this->responder = new Responder();
    }

    public function __invoke(Request $request, Response $response)
    {
        $command = new InstallLampCommand($request->getParsedBody()['location']);
        /** @var \App\Models\Lamp $lamp */
        $lamp = $this->commandBus->handle($command);
        $resource = new Lamp($lamp);
        return $this->responder->respond($response, $resource->toArray(), 201);
    }
}
