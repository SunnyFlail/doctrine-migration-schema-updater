<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\ORM\Mapping\ClassMetadata;

final readonly class MetadataContainer
{
    /**
     * @param array<string, ClassMetadata> $classesMetadatas
     */
    private function __construct(
        private array $classesMetadatas,
    ) {
    }

    public static function create(ClassMetadata ...$classes): self
    {
        $mapped = [];

        foreach ($classes as $classMetadata) {
            $mapped[$classMetadata->getName()] = $classMetadata;
        }

        return new self($mapped);
    }

    /**
     * @param class-string $classFQCN
     *
     * @throws \LogicException
     */
    public function getClassMetadata(string $classFQCN): ClassMetadata
    {
        if (false === isset($this->classesMetadatas[$classFQCN])) {
            throw new \LogicException(sprintf(
                'Class "%s" does not have mapping',
                $classFQCN,
            ));
        }

        return $this->classesMetadatas[$classFQCN];
    }
}
