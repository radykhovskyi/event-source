<?php

declare(strict_types=1);

namespace App\Commands;

class InstallLampCommand
{
    private string $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function location(): string
    {
        return $this->location;
    }
}
