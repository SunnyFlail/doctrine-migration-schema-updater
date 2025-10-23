<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\Mapping\ClassMetadata;

final readonly class EmbeddableSchemaUpdater implements SchemaUpdaterInterface
{
    /**
     * @param iterable<UpdateEmbeddableSchemaInterface> $embeddableSchemaUpdaters
     */
    public function __construct(
        private iterable $embeddableSchemaUpdaters,
    ) {
    }

    public function supports(ClassMetadata $metadata): bool
    {
        return false === $metadata->isEmbeddedClass && false === empty($metadata->embeddedClasses);
    }

    public function update(
        Schema $schema,
        ClassMetadata $metadata,
        MetadataContainer $metadataContainer,
    ): void {
        foreach ($this->embeddableSchemaUpdaters as $embeddableSchemaUpdater) {
            if (false === $embeddableSchemaUpdater instanceof UpdateEmbeddableSchemaInterface) {
                throw WrongServiceException::createForEmbeddableSchemaUpdater($embeddableSchemaUpdater);
            }

            $this->updateClassMetadata(
                $embeddableSchemaUpdater,
                $schema,
                $metadata,
                $metadataContainer,
            );
        }
    }

    private function updateClassMetadata(
        UpdateEmbeddableSchemaInterface $embeddableSchemaUpdater,
        Schema $schema,
        ClassMetadata $metadata,
        MetadataContainer $metadataContainer,
    ): void {
        foreach ($metadata->embeddedClasses as $fieldName => $embeddedClassMapping) {
            if (false === $embeddableSchemaUpdater->supports($embeddedClassMapping)) {
                continue;
            }

            $embeddableSchemaUpdater->update(
                $schema,
                $metadata,
                $fieldName,
                $embeddedClassMapping,
                $metadataContainer,
            );
        }
    }
}
