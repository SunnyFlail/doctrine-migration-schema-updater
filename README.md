## Custom doctrine migrations schema generation

This package allows to add custom schema generation logic,  
that will be included in `doctrine:migrations:diff`,   
and will persist between updates of schema.

### Symfony usage

First register the bundle in your `bundles.php`:

```php
    SunnyFlail\DoctrineMigrationSchemaUpdater\Symfony\DoctrineSchemaUpdaterBundle::class => ['dev' => true],
```

Now it will be ready to work, but it won't do any difference out of the box.

The bundle ships by default with one Schema Updater   
`SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\EmbeddableSchemaUpdater`
it is configured by default to be injected, but it requires some further configuration.
If you want to edit a schema that is generated for each class having an embedded object, then create a
service implementing `SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\UpdateEmbeddableSchemaInterface`
and tag it with `doctrine.migrations.embeddable_schema_updater`.
If you want to implement a schema updated with bigger capabilities,
then implement `SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\SchemaUpdaterInterface` and tag it with `doctrine.migrations.schema_updater`.

On more detailed configuration, see `SunnyFlail\DoctrineMigrationSchemaUpdater\Symfony\DependencyInjection\CompilerPass\CustomConfigureDependencyFactoryPass`.