<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\LampInstalled;
use App\Events\LampLocationChanged;
use App\Events\LampTurnedOff;
use App\Events\LampTurnedOn;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\UuidAggregateRootId;

class Lamp implements AggregateRoot
{
    public const STATE_ON = 'ON';
    public const STATE_OFF = 'OFF';
    public const LOCATION_KITCHEN = 'kitchen';
    private string $state;
    private string $location;

    use AggregateRootBehaviour;

    public static function install(string $location): Lamp
    {
        $id = UuidAggregateRootId::create();
        $process = new static($id);
        $process->recordThat(new LampInstalled($id, $location));
        return $process;
    }

    public static function populate(AggregateRootId $id, string $state, string $location): self
    {
        $model = new self($id);
        $model->state = $state;
        $model->location = $location;
        return $model;
    }

    public function turnOff(): Lamp
    {
        $this->recordThat(new LampTurnedOff($this->aggregateRootId));
        return $this;
    }

    public function turnOn(): Lamp
    {
        $this->recordThat(new LampTurnedOn($this->aggregateRootId));
        return $this;
    }

    public function changeLocation(string $location)
    {
        $this->recordThat(new LampLocationChanged($this->aggregateRootId, $location));
        return $this;
    }

    public function applyLampInstalled(LampInstalled $event)
    {
        $this->state = self::STATE_OFF;
        $this->location = $event->location();
    }

    public function applyLampTurnedOff(LampTurnedOff $event)
    {
        $this->state = self::STATE_OFF;
    }

    public function applyLampTurnedOn(LampTurnedOn $event)
    {
        $this->state = self::STATE_ON;
    }

    public function applyLampLocationChanged(LampLocationChanged $event)
    {
        $this->location = $event->location();
    }

    public function id(): string
    {
        return $this->aggregateRootId->toString();
    }

    public function state(): string
    {
        return $this->state;
    }

    public function location(): string
    {
        return $this->location;
    }
}
