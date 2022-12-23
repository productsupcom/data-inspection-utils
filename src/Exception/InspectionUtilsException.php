<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Exception;

class InspectionUtilsException extends \RuntimeException
{
    protected static string $defaultMessage = 'Inspection utils error occurred';

    final public function __construct(?string $message = null, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?? static::$defaultMessage, $code, $previous);
    }

    final public static function create(?string $message = null, int $code = 0, \Throwable $previous = null): static
    {
        return new static($message, $code, $previous);
    }
}