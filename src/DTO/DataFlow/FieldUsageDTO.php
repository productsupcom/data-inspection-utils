<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DTO\DataFlow;

class FieldUsageDTO
{
    /**
     * @param string $fieldName
     * @param SiteFieldUsageDTO[] $sitesUsages
     */
    public function __construct(
        public readonly string $fieldName,
        private array $sitesUsages = [],
    ) {}

    /**
     * @return SiteFieldUsageDTO[]
     */
    public function getSitesUsages(): array
    {
        return $this->sitesUsages;
    }

    /**
     * @param SiteFieldUsageDTO[] $sitesUsages
     */
    public function setSitesUsages(array $sitesUsages): void
    {
        $this->sitesUsages = $sitesUsages;
    }

    public function addSiteUsage(SiteFieldUsageDTO $siteFieldUsage): void
    {
        $this->sitesUsages[] = $siteFieldUsage;
    }

    public function getTotalUsageCount(): int
    {
        return array_sum(array_map(fn (SiteFieldUsageDTO $siteFieldUsage) => $siteFieldUsage->usagesCount, $this->sitesUsages));
    }

    public function getTotalSitesCount(): int
    {
        return count($this->sitesUsages);
    }
}