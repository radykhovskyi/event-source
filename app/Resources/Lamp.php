<?php

declare(strict_types=1);

namespace App\Resources;

use App\Models\Lamp as Model;

class Lamp
{
    private Model $resource;

    public function __construct(Model $resource)
    {
        $this->resource = $resource;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->resource->id(),
            'state' => $this->resource->state(),
            'location' => $this->resource->location(),
        ];
    }
}
