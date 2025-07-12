<?php

declare(strict_types=1);

namespace Modules\Common\Domain;

use ArrayIterator;
use JsonSerializable;

abstract class ValueObjectArray extends ArrayIterator implements JsonSerializable
{
    abstract public function jsonSerialize(): array;
}
