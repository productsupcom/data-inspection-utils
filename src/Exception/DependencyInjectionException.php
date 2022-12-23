<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Exception;

class DependencyInjectionException extends InspectionUtilsException
{
    protected static string $defaultMessage = 'Failed to build the container with given configuration';
}