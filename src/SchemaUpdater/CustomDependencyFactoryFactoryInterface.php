<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

use Doctrine\Migrations\DependencyFactory;

interface CustomDependencyFactoryFactoryInterface
{
    public function create(): DependencyFactory;
}