<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject\ColumnNames;

final readonly class GenerateIndexName
{
    public function generate(
        string $tableName,
        ColumnNames $columnNames,
    ): string {
        return sprintf(
            'idx_%s_%s',
            $tableName,
            $columnNames->implode('_'),
        );
    }
}