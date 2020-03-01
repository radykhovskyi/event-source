<?php

declare(strict_types=1);

namespace App\Commands;

use EventSauce\EventSourcing\AggregateRootId;

class GetLampQuery
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
}
