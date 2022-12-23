<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DataFetcher\FieldUsage;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Productsup\DataInspectionUtils\DataFetcher\DataFlow\Attribute\FieldUsageFetcher;
use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageDTO;
use Productsup\DataInspectionUtils\DTO\DataFlow\FieldUsageOptionsDTO;
use Productsup\DataInspectionUtils\DTO\DataFlow\SiteFieldUsageDTO;
use Productsup\DataInspectionUtils\DTO\Domain\SiteDTO;
use Productsup\DataInspectionUtils\Enum\FieldOrigin;
use Productsup\DataInspectionUtils\Exception\DatabaseException;

#[FieldUsageFetcher(FieldOrigin::DATA_SOURCE)]
class DataSourceFieldUsageFetcher implements FieldUsageFetcherInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {}
    public function fetchFieldsUsageForSites(array $fieldNames, array $sites, FieldUsageOptionsDTO $options, array &$sourceArray): void
    {
        $serializedFieldNames = $this->prepareFieldNames($fieldNames);
        $sitesMap = array_combine(array_map(fn (SiteDTO $site) => $site->siteId, $sites), $sites);

        try {
            $queryBuilder = $this
                ->connection
                ->createQueryBuilder();

            $query = $queryBuilder
                ->select('dfp.site_id AS siteId', 'dfp.params AS fieldSerialized', 'COUNT(sc.deleted) AS usesCount')
                ->from('pds_df_process', 'dfp')
                ->leftJoin(
                    'dfp',
                    'pds_df_process',
                    'dfp2',
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq('dfp2.mode', $queryBuilder->expr()->literal('output')),
                        $queryBuilder->expr()->eq(
                            'dfp2.params',
                            "CONCAT('a:1:{i:0;s:', LENGTH(dfp.`field`), ':\"', dfp.`field`, '\";}')",
                        ),
                        $queryBuilder->expr()->eq('dfp2.site_id', 'dfp.site_id'),
                    )
                )
                ->leftJoin(
                    'dfp2',
                    'pds_site_channel',
                    'sc',
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq('sc.id', 'dfp2.site_channel_id'),
                        $queryBuilder->expr()->eq('sc.deleted', 0),
                    )
                )
                ->where(
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq('dfp.mode', $queryBuilder->expr()->literal('input')),
                        $queryBuilder->expr()->in('dfp.params', $serializedFieldNames),
                        $queryBuilder->expr()->in('dfp.site_id', array_keys($sitesMap)),
                    )
                );

            if (!$options->includeSitesWhereNotUsed) {
//                $query->andWhere($queryBuilder->expr()->neq('usesCount', 0));
            }

            $query->groupBy('siteId', 'fieldSerialized');
            $fieldsUsageData = $query->fetchAllAssociative();

            foreach ($fieldsUsageData as $fieldUsageData) {
                $fieldName = $this->restoreFieldName($fieldUsageData['fieldSerialized']);
                $siteId = (int) $fieldUsageData['siteId'];
                $usesCount = (int) $fieldUsageData['usesCount'];

                if (!isset($sourceArray[$fieldName])) {
                    $sourceArray[$fieldName] = new FieldUsageDTO($fieldName);
                }

                $sourceArray[$fieldName]->addSiteUsage(new SiteFieldUsageDTO(
                    $sitesMap[$siteId],
                    $usesCount > 0,
                    $usesCount
                ));
            }
        } catch (DBALException $exception) {
            throw DatabaseException::create(sprintf(
                'Failed to load fields usage data from the database. %s', $exception->getMessage()
            ));
        }
    }

    private function prepareFieldNames(array $fieldNames): array
    {
        $queryBuilder = $this
            ->connection
            ->createQueryBuilder();
        $serializedNames = [];

        foreach ($fieldNames as $fieldName) {
            $serializedNames[] = $queryBuilder->expr()->literal(serialize([$fieldName]));
            // TODO: Add boxes configurations (e.g. a:4:{s:6:"column";s:12:"product_type";s:4:"mode";s:6:"append";s:14:"pretext_select";s:7:"prepend";s:7:"pretext";s:0:"";})
        }

        return $serializedNames;
    }

    private function restoreFieldName(string $serializedName): string
    {
        return current(unserialize($serializedName));
    }
}