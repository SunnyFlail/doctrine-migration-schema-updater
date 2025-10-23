<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

enum ColumnNotFoundPolicyEnum
{
    case DO_NOTHING;
    case THROW_EXCEPTION;
}
