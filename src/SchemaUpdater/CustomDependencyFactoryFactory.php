<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\Migrations\Configuration\EntityManager\EntityManagerLoader;
use Doctrine\Migrations\Configuration\Migration\ConfigurationLoader;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Provider\SchemaProvider;
use Psr\Log\LoggerInterface;

final class CustomDependencyFactoryFactory implements CustomDependencyFactoryFactoryInterface
{
    public function __construct(
        private readonly ConfigurationLoader $configurationLoader,
        private readonly EntityManagerLoader $emLoader,
        private readonly LoggerInterface $logger,
        private readonly SchemaProvider $schemaProvider,
    ) {
    }

    public function create(): DependencyFactory
    {
        $dependencyFactory = DependencyFactory::fromEntityManager(
            configurationLoader: $this->configurationLoader,
            emLoader: $this->emLoader,
            logger: $this->logger,
        );

        $reflection = new \ReflectionObject($dependencyFactory);

        $dependenciesReflection = $reflection->getProperty('dependencies');
        $dependenciesReflection->setAccessible(true);
        $currentValue = $dependenciesReflection->getValue($dependencyFactory);
        $currentValue[SchemaProvider::class] = $this->schemaProvider;
        $dependenciesReflection->setValue($dependencyFactory, $currentValue);

        return $dependencyFactory;
    }
}
