<?php

declare(strict_types=1);

namespace App\Exceptions;

use EventSauce\EventSourcing\AggregateRootId;
use RuntimeException;

class LampNotFoundException extends RuntimeException
{
    private AggregateRootId $id;

    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
        parent::__construct();
    }

    public function id(): AggregateRootId
    {
        return $this->id;
    }
}
