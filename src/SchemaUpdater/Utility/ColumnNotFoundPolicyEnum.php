<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Util;

enum ColumnNotFoundPolicyEnum
{
    case DO_NOTHING;
    case THROW_EXCEPTION;
}
