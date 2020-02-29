<?php

declare(strict_types=1);

namespace App\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class LampTurnedOff implements SerializablePayload
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self();
    }
}