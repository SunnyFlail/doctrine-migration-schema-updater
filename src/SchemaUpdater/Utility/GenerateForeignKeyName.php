<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject\ColumnNames;

final readonly class GenerateForeignKeyName
{
    public function generate(
        string $localTableName,
        string $targetTableName,
        ColumnNames $localColumnNames,
        ColumnNames $targetColumnNames,
    ): string {
        return sprintf(
            'fk_%1$s_%2$s_%3$s_%4$s',
            $localTableName,
            $localColumnNames->implode('_'),
            $targetTableName,
            $targetColumnNames->implode('_'),
        );
    }
}
