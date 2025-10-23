<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\MetadataContainer;

final readonly class GetEntityTableSchema
{
    /**
     * @param class-string $classFQCN
     *
     * @throws SchemaException
     * @throws \LogicException
     */
    public function get(
        MetadataContainer $metadataContainer,
        Schema $schema,
        string $classFQCN,
    ): Table {
        $tableName = $metadataContainer->getClassMetadata($classFQCN)->getTableName();

        return $schema->getTable($tableName);
    }
}
