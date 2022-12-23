<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Strategy\DataFlow\Factory;

use Productsup\DataInspectionUtils\Command\DataFlow\FieldUsageFetchCommand;
use Productsup\DataInspectionUtils\DataFetcher\DataFlow\Factory\SiteFetcherFactory;
use Productsup\DataInspectionUtils\DataFetcher\FieldUsage\Factory\FieldUsageFetcherFactory;
use Productsup\DataInspectionUtils\Strategy\DataFlow\FieldUsageInspectionStrategy;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class FieldUsageInspectionStrategyFactory
{
    public function __construct(
        private readonly SiteFetcherFactory $siteFetcherFactory,
        private readonly FieldUsageFetcherFactory $fieldUsageFetcherFactory,
        #[Autowire('%productsup.data_inspection.field_usage.site_chunk_size%')]
        private readonly int $sitesChunkSize,
    ) {}

    public function createFromFieldUsageFetchCommand(FieldUsageFetchCommand $fieldUsageFetchCommand): FieldUsageInspectionStrategy
    {
        return new FieldUsageInspectionStrategy(
            siteFetcher: $this->siteFetcherFactory->getForOrigin($fieldUsageFetchCommand->origin),
            fieldUsageFetcher: $this->fieldUsageFetcherFactory->getForOrigin($fieldUsageFetchCommand->origin),
            fetchCommand: $fieldUsageFetchCommand,
            sitesChunkSize: $this->sitesChunkSize,
        );
    }
}