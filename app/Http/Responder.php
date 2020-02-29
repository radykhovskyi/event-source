<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ResponseInterface as Response;

class Responder
{
    public function respond(Response $response, array $content, int $code = 200): Response
    {
        $response->withStatus($code);
        $response->getBody()->write(\json_encode($content));
        return $response->withHeader('Content-Type', 'application/json');
    }
}