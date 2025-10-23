<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Util;

class NotExistingColumnException extends \RuntimeException
{
    public function __construct(
        public readonly string $tableName,
        public readonly string $columnName,
    ) {
        parent::__construct(sprintf(
            'Column "%s" does not exist in table "%s"',
            $columnName,
            $tableName,
        ));
    }
}