<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DataFetcher\FieldUsage\Factory;

use Productsup\DataInspectionUtils\DataFetcher\FieldUsage\FieldUsageFetcherInterface;
use Productsup\DataInspectionUtils\Enum\FieldOrigin;
use Productsup\DataInspectionUtils\Exception\DependencyInjectionException;

class FieldUsageFetcherFactory
{
    /**
     * @var array<string, FieldUsageFetcherInterface> the origin type - field usage fetcher map
     */
    private array $fieldUsageFetcherMap = [];

    public function pushFieldUsageFetcher(FieldOrigin $origin, FieldUsageFetcherInterface $fieldUsageFetcher): void
    {
        if (isset($this->fieldUsageFetcherMap[$origin->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to register more than one field usage fetcher for field origin "%s" [%s | %s]',
                $origin->value,
                $this->fieldUsageFetcherMap[$origin->value]::class,
                $fieldUsageFetcher::class,
            ));
        }

        $this->fieldUsageFetcherMap[$origin->value] = $fieldUsageFetcher;
    }

    public function getForOrigin(FieldOrigin $origin): FieldUsageFetcherInterface
    {
        if (!isset($this->fieldUsageFetcherMap[$origin->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to fetch field usage fetcher for field origin "%s" which is not configured',
                $origin->value,
            ));
        }

        return $this->fieldUsageFetcherMap[$origin->value];
    }
}
