<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\GetLampQuery;
use App\Exceptions\LampNotFoundException;
use App\Models\Lamp;
use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\UuidAggregateRootId;

class GetLampHandler
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @param GetLampQuery $query
     * @return Lamp
     *
     * @throws LampNotFoundException
     */
    public function handle(GetLampQuery $query): Lamp
    {
        $data = $this->db->fetchAssoc('SELECT * FROM `lamps` WHERE `aggregate_root_id` = ?', [$query->id()->toString()]);
        if ($data === false) {
            throw new LampNotFoundException($query->id());
        }
        return Lamp::populate(UuidAggregateRootId::fromString($data['aggregate_root_id']), $data['state'], $data['location']);
    }
}
