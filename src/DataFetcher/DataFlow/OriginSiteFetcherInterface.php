<?php

namespace Productsup\DataInspectionUtils\DataFetcher\DataFlow;

use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageOptionsDTO;
use Productsup\DataInspectionUtils\DTO\Domain\SiteDTO;

interface OriginSiteFetcherInterface
{
    /**
     * @return SiteDTO[]
     */
    public function getAllForOriginId(int $originId, FieldUsageOptionsDTO $options): array;
}
