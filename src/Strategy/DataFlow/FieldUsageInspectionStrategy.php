<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Strategy\DataFlow;

use Productsup\DataInspectionUtils\Command\DataFlow\FieldUsageFetchCommand;
use Productsup\DataInspectionUtils\DataFetcher\DataFlow\OriginSiteFetcherInterface;
use Productsup\DataInspectionUtils\DataFetcher\FieldUsage\FieldUsageFetcherInterface;
use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageDTO;
use Productsup\DataInspectionUtils\Exception\InspectionUtilsException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class FieldUsageInspectionStrategy
{
    public function __construct(
        private readonly OriginSiteFetcherInterface $siteFetcher,
        private readonly FieldUsageFetcherInterface $fieldUsageFetcher,
        private readonly FieldUsageFetchCommand $fetchCommand,
        private readonly int $sitesChunkSize,
    ) {}

    /**
     * @return FieldUsageDTO[]
     */
    public function execute(): array
    {
        $sites = $this->siteFetcher->getAllForOriginId($this->fetchCommand->originId, $this->fetchCommand->options);

        if (empty($sites)) {
            throw InspectionUtilsException::create('There are no active sites using the given data origin');
        }

        $fieldsUsage = [];
        $sitesChunks = array_chunk($sites, $this->sitesChunkSize);

        foreach ($sitesChunks as $sitesChunk) {
            $this->fieldUsageFetcher->fetchFieldsUsageForSites(
                fieldNames: $this->fetchCommand->fieldKeys,
                sites: $sitesChunk,
                options: $this->fetchCommand->options,
                sourceArray: $fieldsUsage
            );
        }

        return array_values($fieldsUsage);
    }
}
