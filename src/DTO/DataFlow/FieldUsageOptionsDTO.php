<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DTO\DataFlow;

class FieldUsageOptionsDTO
{
    public function __construct(
        public readonly bool $includeSitesWhereNotUsed = false,
        public readonly bool $activeSitesOnly = true,
    ) {}
}
