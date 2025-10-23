<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject\ColumnNames;
use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject\ForeignKeyOptions;
use Doctrine\DBAL\Schema\Table;

final readonly class AddForeignKeyToTableSchema
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
        Table $localTable,
        Table $targetTable,
        ColumnNames $localColumnNames,
        ColumnNames $targetColumnNames,
        ?string $constraintName = null,
        ?ForeignKeyOptions $options = null,
        ColumnNotFoundPolicyEnum $localColumnPolicy = ColumnNotFoundPolicyEnum::DO_NOTHING,
        ColumnNotFoundPolicyEnum $targetColumnPolicy = ColumnNotFoundPolicyEnum::DO_NOTHING,
    ): void {
        $constraintName ??= sprintf(
            'fk_%s_%s',
            $localTable->getName(),
            implode('_', $localColumnNames->columns),
        );

        if (
            true === $localTable->hasForeignKey($constraintName)
            || false === $this->validateTableColumns->validate(
                $localTable,
                $localColumnNames,
                $localColumnPolicy,
            )
            || false === $this->validateTableColumns->validate(
                $targetTable,
                $targetColumnNames,
                $targetColumnPolicy,
            )
        ) {
            return;
        }

        $localTable->addForeignKeyConstraint(
            $targetTable->getName(),
            localColumnNames: $localColumnNames->columns,
            foreignColumnNames: $targetColumnNames->columns,
            options: $options?->toArray() ?? [],
            name: $constraintName,
        );

        $this->logger->debug(
            'Adding foreign key',
            [
                'foreignKeyName' => $constraintName,
                'tableName' => $localTable->getName(),
                'columnNames' => $localColumnNames->columns,
                'referencedTableName' => $targetTable,
                'referencedColumnNames' => $targetColumnNames->columns,
            ],
        );
    }
}
