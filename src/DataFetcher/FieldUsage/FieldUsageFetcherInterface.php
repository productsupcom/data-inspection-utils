<?php

namespace Productsup\DataInspectionUtils\DataFetcher\FieldUsage;

use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageOptionsDTO;
use Productsup\DataInspectionUtils\DTO\DataFlow\SiteFieldUsageDTO;
use Productsup\DataInspectionUtils\DTO\Domain\SiteDTO;

interface FieldUsageFetcherInterface
{
    /**
     * @param string[] $fieldNames
     * @param SiteDTO[] $sites
     * @param SiteFieldUsageDTO[] $sourceArray
     */
    public function fetchFieldsUsageForSites(array $fieldNames, array $sites, FieldUsageOptionsDTO $options, array &$sourceArray): void;
}