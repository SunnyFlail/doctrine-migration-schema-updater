<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\Mapping\ClassMetadata;

interface SchemaUpdaterInterface
{
    public function supports(ClassMetadata $metadata): bool;

    /**
     * @throws \LogicException
     * @throws WrongServiceException
     */
    public function update(
        Schema $schema,
        ClassMetadata $metadata,
        MetadataContainer $metadataContainer,
    ): void;
}
