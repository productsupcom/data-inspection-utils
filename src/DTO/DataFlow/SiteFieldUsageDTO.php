<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DTO\DataFlow;

use Productsup\DataInspectionUtils\DTO\Domain\SiteDTO;

class SiteFieldUsageDTO
{
    public function __construct(
        public readonly SiteDTO $site,
        public readonly bool $isUsed,
        public readonly int $usagesCount,
    ) {}
}