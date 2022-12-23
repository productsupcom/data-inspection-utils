<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\DependencyInjection\Compiler;

use Productsup\DataInspectionUtils\DataFetcher\DataFlow\Attribute\FieldUsageFetcher;
use Productsup\DataInspectionUtils\DataFetcher\FieldUsage\Factory\FieldUsageFetcherFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FieldUsageFetcherCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $fieldUsageFetcherFactoryDefinition = $container->getDefinition(FieldUsageFetcherFactory::class);

        foreach ($container->findTaggedServiceIds('productsup.data_inspection.field_usage_fetcher') as $fieldUsageFetcherId => $tags) {
            $fieldUsageFetcherDefinition = $container->getDefinition($fieldUsageFetcherId);
            /** @phpstan-ignore-next-line */
            $fieldUsageFetcherClassReflection = new \ReflectionClass($fieldUsageFetcherDefinition->getClass());

            foreach ($fieldUsageFetcherClassReflection->getAttributes() as $attributeReflection) {
                $attribute = $attributeReflection->newInstance();

                if (!$attribute instanceof FieldUsageFetcher) {
                    continue;
                }

                $fieldUsageFetcherFactoryDefinition->addMethodCall('pushFieldUsageFetcher', [
                    $attribute->fieldOrigin,
                    new Reference($fieldUsageFetcherId),
                ]);
            }
        }
    }
}
