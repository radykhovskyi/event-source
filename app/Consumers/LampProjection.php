<?php

declare(strict_types=1);

namespace App\Consumers;

use App\Events\LampInstalled;
use App\Events\LampLocationChanged;
use App\Events\LampTurnedOff;
use App\Events\LampTurnedOn;
use App\Models\Lamp;
use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;

class LampProjection implements Consumer
{
    private Connection $db;
    private const TABLE_NAME = 'lamps';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function handle(Message $message): void
    {
        $event = $message->event();

        if ($event instanceof LampInstalled) {
            $this->db->insert(
                self::TABLE_NAME,
                [
                    'aggregate_root_id' => $event->id()->toString(),
                    'location' => $event->location(),
                    'state' => Lamp::STATE_OFF,
                ]
            );
         } else if ($event instanceof LampLocationChanged) {
            $this->updateLocation($event->id(), $event->location());
        } else if ($event instanceof LampTurnedOn) {
            $this->updateState($event->id(), Lamp::STATE_ON);
        } else if ($event instanceof LampTurnedOff) {
            $this->updateState($event->id(), Lamp::STATE_OFF);
        }
    }

    private function updateLocation(AggregateRootId $id, string $location): void
    {
        $this->db->update(
            self::TABLE_NAME,
            ['location' => $location],
            ['aggregate_root_id' => $id->toString()]
        );
    }

    private function updateState(AggregateRootId $id, string $state): void
    {
        $this->db->update(
            self::TABLE_NAME,
            ['state' => $state],
            ['aggregate_root_id' => $id->toString()]
        );
    }
}
