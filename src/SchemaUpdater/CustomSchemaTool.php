<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\DBAL\Schema\Schema;

final class CustomSchemaTool extends SchemaTool
{
    /**
     * @param iterable<SchemaUpdaterInterface> $schemaUpdaters
     */
    public function __construct(
        EntityManagerInterface $em,
        private readonly iterable $schemaUpdaters,
    ) {
        parent::__construct($em);
    }

    public function getSchemaFromMetadata(array $classes): Schema
    {
        $schema = parent::getSchemaFromMetadata($classes);
        $metadataContainer = MetadataContainer::create(...$classes);

        foreach ($this->schemaUpdaters as $updater) {
            if (false === $updater instanceof SchemaUpdaterInterface) {
                throw WrongServiceException::createForSchemaUpdater($updater);
            }

            $this->updateSchemaForClasses(
                $updater,
                $schema,
                $classes,
                $metadataContainer,
            );
        }

        return $schema;
    }

    /**
     * @param list<ClassMetadata> $metadataList
     */
    private function updateSchemaForClasses(
        SchemaUpdaterInterface $schemaUpdater,
        Schema $schema,
        array $metadataList,
        MetadataContainer $metadataContainer,
    ): void {
        foreach ($metadataList as $metadata) {
            if (false === $schemaUpdater->supports($metadata)) {
                continue;
            }

            $schemaUpdater->update($schema, $metadata, $metadataContainer);
        }
    }
}
