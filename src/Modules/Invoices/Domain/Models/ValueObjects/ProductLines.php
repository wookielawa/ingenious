<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Models\ValueObjects;

use Modules\Common\Domain\ValueObjectArray;
use Modules\Invoices\Domain\Models\ProductLine;

class ProductLines extends ValueObjectArray
{
    public readonly array $productLines;

    public function __construct(array $productLines)
    {
        parent::__construct($productLines);

        foreach ($productLines as $productLine) {
            if ( ! $productLine instanceof ProductLine) {
                throw new \InvalidArgumentException('Invalid product line');
            }
        }

        $this->productLines = array_values($productLines);
    }

    public function add(ProductLine $productLine): void
    {
        $this->append($productLine);
    }

    public function total(): float
    {
        $total = 0.0;

        /** @var ProductLine $productLine */
        foreach ($this->productLines as $productLine) {
            $total += $productLine->total();
        }

        return  $total;
    }

    public function toArray(): array
    {
        return $this->productLines;
    }

    public function jsonSerialize(): array
    {
        return $this->productLines;
    }
}
