<?php

declare(strict_types=1);

namespace Productsup\DataInspectionUtils\Enum;

enum FieldOrigin: string
{
    case DATA_SOURCE = 'data-source';
    case EXPORT_CHANNEL = 'export-channel';
}
