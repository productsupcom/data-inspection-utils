<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Command\DataFlow;

use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageOptionsDTO;
use Productsup\DataInspectionUtils\Enum\FieldOrigin;

class FieldUsageFetchCommand
{
    /**
     * @param string[] $fieldKeys
     */
    public function __construct(
        public readonly FieldOrigin $origin,
        public readonly int $originId,
        public readonly array $fieldKeys,
        public readonly FieldUsageOptionsDTO $options,
    ) {}
}
