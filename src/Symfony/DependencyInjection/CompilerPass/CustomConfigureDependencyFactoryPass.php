<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\Symfony\DependencyInjection\CompilerPass;

use Doctrine\Migrations\DependencyFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class CustomConfigureDependencyFactoryPass implements CompilerPassInterface
{
    public const DEPENDENCY_FACTORY_FACTORY_ALIAS = 'doctrine.migrations.overrides.dependency_factory_factory';

    public function process(ContainerBuilder $container): void
    {
        $this->overrideDependencyFactoryCreation($container);
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
}
