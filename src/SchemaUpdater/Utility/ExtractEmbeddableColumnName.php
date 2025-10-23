<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use Doctrine\ORM\Mapping\ClassMetadata;

final readonly class ExtractEmbeddableColumnName
{
    public function extract(
        ClassMetadata $metadata,
        string $embeddableName,
        string $columnName
    ): string {
        $name = sprintf('%s.%s', $embeddableName, $columnName);

        if (false === isset($metadata->columnNames[$name])) {
            throw new \LogicException(sprintf(
                'Field "%s" does not exist in mapping of %s',
                $name,
                $metadata->name,
            ));
        }

        return $metadata->columnNames[$name];
    }
}
