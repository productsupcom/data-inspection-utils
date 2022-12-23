<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DataFetcher\DataFlow\Factory;

use Productsup\DataInspectionUtils\DataFetcher\DataFlow\OriginSiteFetcherInterface;
use Productsup\DataInspectionUtils\Enum\FieldOrigin;
use Productsup\DataInspectionUtils\Exception\DependencyInjectionException;

class SiteFetcherFactory
{
    /**
     * @var array<string, OriginSiteFetcherInterface> the origin type - site fetcher map
     */
    private array $siteFetcherMap = [];

    public function pushSiteFetcher(FieldOrigin $origin, OriginSiteFetcherInterface $siteFetcher): void
    {
        if (isset($this->siteFetcherMap[$origin->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to register more than one site fetcher for field origin "%s" [%s | %s]',
                $origin->value,
                $this->siteFetcherMap[$origin->value]::class,
                $siteFetcher::class,
            ));
        }

        $this->siteFetcherMap[$origin->value] = $siteFetcher;
    }

    public function getForOrigin(FieldOrigin $origin): OriginSiteFetcherInterface
    {
        if (!isset($this->siteFetcherMap[$origin->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to fetch site fetcher for field origin "%s" which is not configured',
                $origin->value,
            ));
        }

        return $this->siteFetcherMap[$origin->value];
    }
}
