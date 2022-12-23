<?php

namespace Productsup\DataInspectionUtils\DataFetcher\DataFlow\Attribute;

use Productsup\DataInspectionUtils\Enum\FieldOrigin;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class FieldUsageFetcher
{
    public function __construct(public readonly FieldOrigin $fieldOrigin)
    {}
}