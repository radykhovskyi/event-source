<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\LampInstalled;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;

class Lamp implements AggregateRoot
{
    const STATE_ON = 'ON';
    const STATE_OFF = 'OFF';
    const LOCATION_KITCHEN = 'kitchen';
    public $state;
    public $location;
    use AggregateRootBehaviour;

    public static function install(AggregateRootId $id, $location): Lamp
    {
        $process = new static($id);
        $process->recordThat(new LampInstalled($id, $location));
        return $process;
    }
    public function applyLampInstalled(LampInstalled $event)
    {
        $this->location = $event->location();
    }
}