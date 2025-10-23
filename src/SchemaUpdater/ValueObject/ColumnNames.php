<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject;

final readonly class ColumnNames
{
    /**
     * @var list<string> $columns
     */
    public array $columns;

    public function __construct(string ...$columns)
    {
        if (true === empty($columns)) {
            throw new \InvalidArgumentException('Column names must not be empty.');
        }

        $this->columns = $columns;
    }

    public function findFirst(callable|ColumnNamesFilterCallback $callback): ?string
    {
        foreach ($this->columns as $column) {
            if (true === $callback($column)) {
                return $column;
            }
        }

        return null;
    }
}
