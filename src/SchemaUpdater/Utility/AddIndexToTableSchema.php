<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject\ColumnNames;
use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject\IndexFlags;
use Doctrine\DBAL\Schema\Table;

final readonly class AddIndexToTableSchema
{
    public function __construct(
        private ValidateTableColumns $validateTableColumns,
        private GenerationLogger $logger,
    ) {
    }

    /**
     * @throws NotExistingColumnException
     */
    public function add(
        Table $tableSchema,
        ColumnNames $columnNames,
        ?string $indexName = null,
        ?IndexFlags $indexFlags = null,
        ColumnNotFoundPolicyEnum $columnPolicy = ColumnNotFoundPolicyEnum::DO_NOTHING,
    ): void {
        $indexName ??= sprintf(
            '%s_idx',
            implode('_', $columnNames->columns),
        );

        if (
            true === $tableSchema->hasIndex($indexName)
            || false === $this->validateTableColumns->validate(
                $tableSchema,
                $columnNames,
                $columnPolicy,
            )
        ) {
            return;
        }

        $tableSchema->addIndex(
            columnNames: $columnNames->columns,
            indexName: $indexName,
            flags: $indexFlags?->toArray() ?? [],
            options: [],
        );

        $this->logger->debug(
            'Adding index',
            [
                'indexName' => $indexName,
                'tableName' => $tableSchema->getName(),
                'columnNames' => $columnNames->columns,
            ]
        );
    }
}
