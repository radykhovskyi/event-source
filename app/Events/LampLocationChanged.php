<?php

declare(strict_types=1);

namespace App\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class LampLocationChanged implements SerializablePayload
{
    private string $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function toPayload(): array
    {
        return [
            'location' => $this->location
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self($payload['location']);
    }
}