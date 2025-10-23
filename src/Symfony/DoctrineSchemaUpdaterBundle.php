<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\Symfony;

use Doctrine\Bundle\MigrationsBundle\DependencyInjection\CompilerPass\ConfigureDependencyFactoryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

if (false === class_exists(Bundle::class)) {
    throw new \LogicException('The DoctrineSchemaUpdaterBundle may only be used with Symfony.');
}

class DoctrineSchemaUpdaterBundle extends Bundle
{
    /** @return void */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigureDependencyFactoryPass());
    }

    public function getPath(): string
    {
        return dirname(__DIR__, 2);
    }
}