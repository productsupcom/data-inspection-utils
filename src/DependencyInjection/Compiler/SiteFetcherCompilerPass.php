<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DependencyInjection\Compiler;

use Productsup\DataInspectionUtils\DataFetcher\DataFlow\Attribute\SiteFetcher;
use Productsup\DataInspectionUtils\DataFetcher\DataFlow\Factory\SiteFetcherFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SiteFetcherCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $siteFetcherFactoryDefinition = $container->getDefinition(SiteFetcherFactory::class);

        foreach ($container->findTaggedServiceIds('productsup.data_inspection.site_fetcher') as $siteFetcherId => $tags) {
            $siteFetcherDefinition = $container->getDefinition($siteFetcherId);
            /** @phpstan-ignore-next-line */
            $siteFetcherClassReflection = new \ReflectionClass($siteFetcherDefinition->getClass());

            foreach ($siteFetcherClassReflection->getAttributes() as $attributeReflection) {
                $attribute = $attributeReflection->newInstance();

                if (!$attribute instanceof SiteFetcher) {
                    continue;
                }

                $siteFetcherFactoryDefinition->addMethodCall('pushSiteFetcher', [
                    $attribute->fieldOrigin,
                    new Reference($siteFetcherId),
                ]);
            }
        }
    }
}
