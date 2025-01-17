<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\IdentifiableEntityInterface;
use App\Traits\NameableEntityInterface;

interface ApplicationConfigInterface extends IdentifiableEntityInterface, NameableEntityInterface
{
    public function getValue(): string;
    public function setValue(string $value);
}
