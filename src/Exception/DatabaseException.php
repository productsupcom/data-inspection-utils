<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Exception;

class DatabaseException extends InspectionUtilsException
{
    protected static string $defaultMessage = 'Failed to fetch the data from the database';
}