<?php

declare(strict_types=1);

namespace App\Commands;

use EventSauce\EventSourcing\AggregateRootId;

class UpdateLampCommand
{
    private AggregateRootId $id;
    private ?string $state;
    private ?string $location;

    public function __construct(AggregateRootId $id, ?string $state, ?string $location)
    {
        $this->id = $id;
        $this->state = $state;
        $this->location = $location;
    }

    public function id(): AggregateRootId
    {
        return $this->id;
    }

    public function state(): ?string
    {
        return $this->state;
    }

    public function location(): ?string
    {
        return $this->location;
    }
}
