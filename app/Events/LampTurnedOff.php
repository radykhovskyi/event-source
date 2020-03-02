<?php

declare(strict_types=1);

namespace App\Events;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use EventSauce\EventSourcing\UuidAggregateRootId;

class LampTurnedOff implements SerializablePayload
{
    private AggregateRootId $id;

    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
    }

    public function id(): AggregateRootId
    {
        return $this->id;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(UuidAggregateRootId::fromString($payload['id']));
    }
}
