<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DataFetcher\DataFlow;

use Productsup\DataInspectionUtils\DataFetcher\DataFlow\Attribute\SiteFetcher;
use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageOptionsDTO;
use Productsup\DataInspectionUtils\DTO\Domain\SiteDTO;
use Productsup\DataInspectionUtils\Enum\FieldOrigin;

#[SiteFetcher(FieldOrigin::EXPORT_CHANNEL)]
class ExportChannelSiteFetcher implements OriginSiteFetcherInterface
{
    public function getAllForOriginId(int $originId, FieldUsageOptionsDTO $options): array
    {
        // TODO: Implement getAllForOriginId() method.

        return [];
    }
}
