<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Provider\SchemaProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

final readonly class CustomSchemaProvider implements SchemaProvider
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CustomSchemaTool $schemaTool,
    ) {
    }

    public function createSchema(): Schema
    {
        /** @var array<int, ClassMetadata<object>> $metadata */
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        usort(
            $metadata,
            static fn (ClassMetadata $a, ClassMetadata $b): int => $a->getTableName() <=> $b->getTableName()
        );

        return $this->schemaTool->getSchemaFromMetadata($metadata);
    }
}
