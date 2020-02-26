<?php

declare(strict_types=1);

namespace App\Events;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use EventSauce\EventSourcing\UuidAggregateRootId;

class LampInstalled implements SerializablePayload
{
    private string $location;
    private AggregateRootId $uid;

    public function __construct(AggregateRootId $uid, $location)
    {
        $this->uid = $uid;
        $this->location = $location;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function toPayload(): array
    {
        return [
            'uid' => $this->uid->toString(),
            'state' => $this->state,
            'location' => $this->location
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(UuidAggregateRootId::fromString($payload['uid']), $payload['state'], $payload['location']);
    }
}