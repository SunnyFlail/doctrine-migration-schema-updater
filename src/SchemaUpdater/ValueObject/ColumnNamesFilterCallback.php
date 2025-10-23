<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject;

interface ColumnNamesFilterCallback
{
    public function __invoke(string $columnName): bool;
}
