<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\EmbeddedClassMapping;

interface UpdateEmbeddableSchemaInterface
{
    public function supports(EmbeddedClassMapping $embeddedClassMapping): bool;

    public function update(
        Schema $schema,
        ClassMetadata $metadata,
        string $fieldName,
        EmbeddedClassMapping $embeddedClassMapping,
        MetadataContainer $metadataContainer,
    ): void;
}
