<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\Symfony\DependencyInjection\CompilerPass;

use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\CustomDependencyFactoryFactory;
use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\CustomSchemaTool;
use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\CustomSchemaProvider;
use SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\EmbeddableSchemaUpdater;
use Doctrine\Migrations\DependencyFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class CustomConfigureDependencyFactoryPass implements CompilerPassInterface
{
    public const DEPENDENCY_FACTORY_FACTORY_ALIAS = 'doctrine.migrations.overrides.dependency_factory_factory';
    public const DEFAULT_CONFIGURATION_LOADER_ALIAS = 'doctrine.migrations.overrides.configuration_loader';
    public const DEFAULT_LOGGER_ALIAS = 'doctrine.migrations.overrides.logger';
    public const DEFAULT_ENTITY_MANAGER_LOADER_ALIAS = 'doctrine.migrations.overrides.entity_manager_registry_loader';
    public const DEFAULT_ENTITY_MANAGER_ALIAS = 'doctrine.migrations.overrides.entity_manager';
    public const SCHEMA_PROVIDER_ALIAS = 'doctrine.migrations.overrides.schema_provider';
    public const SCHEMA_TOOL_ALIAS = 'doctrine.migrations.overrides.schema_tool';
    public const SCHEMA_UPDATER_TAG = 'doctrine.migrations.schema_updater';

    public function process(ContainerBuilder $container): void
    {
        $this->overrideDependencyFactoryCreation($container);
        $this->setupDefaultAliasIfNotDefined(
            $container,
            self::DEFAULT_CONFIGURATION_LOADER_ALIAS,
            'doctrine.migrations.configuration_loader',
        );
        $this->setupDefaultAliasIfNotDefined(
            $container,
            self::DEFAULT_ENTITY_MANAGER_LOADER_ALIAS,
            'doctrine.migrations.entity_manager_registry_loader',
        );
        $this->setupDefaultAliasIfNotDefined(
            $container,
            self::DEFAULT_ENTITY_MANAGER_ALIAS,
            'doctrine.orm.entity_manager',
        );
        $this->setupDefaultAliasIfNotDefined(
            $container,
            self::DEFAULT_LOGGER_ALIAS,
            'logger',
        );
        $this->setupSchemaProviderIfNotDefined($container);
        $this->setupSchemaToolIfNotDefined($container);
        $this->setupDependencyFactoryFactoryIfNotDefined($container);
    }

    private function overrideDependencyFactoryCreation(ContainerBuilder $container): void
    {
        $diDefinition = $container->getDefinition('doctrine.migrations.dependency_factory');

        $factory = $diDefinition->getFactory();

        if (
            null === $factory
            || false === is_array($factory)
            || 2 !== count($factory)
            || DependencyFactory::class !== $factory[0]
            || 'fromEntityManager' !== $factory[1]
        ) {
            return;
        }

        $diDefinition->setFactory([
            new Reference(self::DEPENDENCY_FACTORY_FACTORY_ALIAS),
            'create'
        ]);
    }

    private function setupDependencyFactoryFactoryIfNotDefined(ContainerBuilder $container): void
    {
        if (true === $this->isDefined($container, self::DEPENDENCY_FACTORY_FACTORY_ALIAS)) {
            return;
        }

        $diDefinition = new Definition(
            CustomDependencyFactoryFactory::class,
            [
                new Reference(self::DEFAULT_CONFIGURATION_LOADER_ALIAS),
                new Reference(self::DEFAULT_ENTITY_MANAGER_LOADER_ALIAS),
                new Reference(self::DEFAULT_LOGGER_ALIAS),
                new Reference(self::SCHEMA_PROVIDER_ALIAS),
            ]
        );
        $container->setDefinition(
            self::DEPENDENCY_FACTORY_FACTORY_ALIAS,
            $diDefinition,
        );
    }

    private function setupSchemaProviderIfNotDefined(ContainerBuilder $container): void
    {
        if (true === $this->isDefined($container, self::SCHEMA_PROVIDER_ALIAS)) {
            return;
        }

        $diDefinition = new Definition(
            CustomSchemaProvider::class,
            [
                new Reference(self::DEFAULT_ENTITY_MANAGER_ALIAS),
                new Reference(self::SCHEMA_TOOL_ALIAS)
            ]
        );
        $container->setDefinition(
            self::SCHEMA_PROVIDER_ALIAS,
            $diDefinition,
        );
    }

    private function setupSchemaToolIfNotDefined(ContainerBuilder $container): void
    {
        if (true === $this->isDefined($container, self::SCHEMA_TOOL_ALIAS)) {
            return;
        }

        $this->setupDefaultSchemaUpdater($container);

        $diDefinition = new Definition(
            CustomSchemaTool::class,
            [
                new Reference(self::DEFAULT_ENTITY_MANAGER_ALIAS),
                $this->getReferenceToAllTaggedServices($container, self::SCHEMA_UPDATER_TAG),
            ]
        );
        $container->setDefinition(
            self::SCHEMA_TOOL_ALIAS,
            $diDefinition,
        );
    }

    private function setupDefaultSchemaUpdater(ContainerBuilder $container): void
    {
        $diDefinition = new Definition(
            EmbeddableSchemaUpdater::class,
            [
                $this->getReferenceToAllTaggedServices($container, 'doctrine.migrations.embeddable_schema_updater'),
            ]
        );

        $diDefinition->addTag(self::SCHEMA_UPDATER_TAG);
        $container->setDefinition(
            EmbeddableSchemaUpdater::class,
            $diDefinition,
        );
    }

    private function setupDefaultAliasIfNotDefined(
        ContainerBuilder $container,
        string $alias,
        string $resolvesTo,
    ): void {
        if (true === $this->isDefined($container, $alias)) {
            return;
        }

        $container->setAlias($alias, $resolvesTo);
    }

    private function isDefined(ContainerBuilder $container, string $alias): bool
    {
        return true === $container->hasDefinition($alias) || true === $container->hasAlias($alias);
    }

    /**
     * @return list<Reference>
     */
    private function getReferenceToAllTaggedServices(ContainerBuilder $container, string $tag): array
    {
        $taggedServices = [];

        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            $taggedServices[] = new Reference($id);
        }

        return $taggedServices;
    }
}
