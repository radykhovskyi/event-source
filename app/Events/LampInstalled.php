<?php

declare(strict_types=1);

namespace App\Events;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use EventSauce\EventSourcing\UuidAggregateRootId;

class LampInstalled implements SerializablePayload
{
    private string $location;
    private AggregateRootId $id;

    public function __construct(AggregateRootId $id, $location)
    {
        $this->id = $id;
        $this->location = $location;
    }

    public function id(): AggregateRootId
    {
        return $this->id;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'location' => $this->location
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(UuidAggregateRootId::fromString($payload['id']), $payload['location']);
    }
}
