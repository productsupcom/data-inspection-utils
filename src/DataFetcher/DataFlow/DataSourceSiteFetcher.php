<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DataFetcher\DataFlow;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\ParameterType;
use Productsup\DataInspectionUtils\DataFetcher\DataFlow\Attribute\SiteFetcher;
use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageOptionsDTO;
use Productsup\DataInspectionUtils\DTO\Domain\SiteDTO;
use Productsup\DataInspectionUtils\Enum\FieldOrigin;
use Productsup\DataInspectionUtils\Exception\DatabaseException;

#[SiteFetcher(FieldOrigin::DATA_SOURCE)]
class DataSourceSiteFetcher implements OriginSiteFetcherInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function getAllForOriginId(int $originId, FieldUsageOptionsDTO $options): array
    {
        try {
            $queryBuilder = $this
                ->connection
                ->createQueryBuilder();

            $query = $queryBuilder
                ->select('psi.site_id as siteId', 'ps.`domain` AS name')
                ->from('pds_project_site_import', 'psi')
                ->leftJoin(
                    'psi',
                    'pds_project_site',
                    'ps',
                    $queryBuilder->expr()->eq('ps.id', 'psi.site_id')
                )
                ->where('psi.import_id = :import_id');

            if ($options->activeSitesOnly) {
                $query->andWhere('ps.is_active != 0');
            }

            $sitesData = $query
                ->setParameter('import_id', $originId, ParameterType::INTEGER)
                ->fetchAllAssociative();

            return array_map(fn (array $siteData) => new SiteDTO(...$siteData), $sitesData);

        } catch (DBALException $exception) {
            throw DatabaseException::create(sprintf(
                'Failed to load sites data from the database. %s', $exception->getMessage()
            ));
        }
    }
}
