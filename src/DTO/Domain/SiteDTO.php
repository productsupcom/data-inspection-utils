<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DTO\Domain;

class SiteDTO
{
    public function __construct(
        public readonly int $siteId,
        public readonly string $name,
    ) {}
}