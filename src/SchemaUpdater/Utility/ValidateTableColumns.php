<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use Doctrine\DBAL\Schema\Table;
use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject\ColumnNames;

final readonly class ValidateTableColumns
{
    /**
     * @throws NotExistingColumnException
     */
    public function validate(
        Table $tableSchema,
        ColumnNames $columns,
        ColumnNotFoundPolicyEnum $columnPolicy,
    ): bool {
        $notExistingColumn = $columns->findFirst(
            fn (string $column): bool => false === $tableSchema->hasColumn($column),
        );

        if (null !== $notExistingColumn) {
            return match ($columnPolicy) {
                ColumnNotFoundPolicyEnum::THROW_EXCEPTION => throw new NotExistingColumnException(
                    $tableSchema->getName(),
                    $notExistingColumn,
                ),
                ColumnNotFoundPolicyEnum::DO_NOTHING => false,
            };
        }

        return true;
    }
}