<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Responder;
use App\Resources\Lamp;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\UuidAggregateRootId;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetLampAction
{
    private AggregateRootRepository $repository;
    private Responder $responder;

    // @todo inject dependencies in proper way
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get('repository');
        $this->responder = new Responder();
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        /** @var \App\Models\Lamp $lamp */
        $lamp = $this->repository->retrieve(new UuidAggregateRootId($id));
        if ($lamp->aggregateRootVersion() === 0) {
            throw new HttpNotFoundException($request, "Lamp with id {$id} not found");
        }
        $resource = new Lamp($lamp);
        return $this->responder->respond($response, $resource->toArray());
    }
}
