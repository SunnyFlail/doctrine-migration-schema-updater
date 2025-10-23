# Custom Doctrine Migrations Schema Generation

This package provides a flexible way to introduce **custom schema generation logic** that integrates seamlessly with the `doctrine:migrations:diff` command.  
Custom changes applied through this mechanism will **persist across schema updates**, ensuring your modifications are retained automatically.

---

## Symfony Integration

### 1. Register the Bundle

Add the bundle to your `config/bundles.php` file:

```php
SunnyFlail\DoctrineMigrationSchemaUpdater\Symfony\DoctrineSchemaUpdaterBundle::class => ['dev' => true],
```

Once registered, the bundle is active — but note that it does not alter your schema by default.

---

### 2. Default Schema Updater

The bundle includes one built-in schema updater:

[`SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\EmbeddableSchemaUpdater`](./src/SchemaUpdater/EmbeddableSchemaUpdater.php)

This updater is automatically available for dependency injection but requires additional configuration to function properly.

If you want to **modify the schema generated for entities containing embedded objects**, implement the following interface:

[`SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\UpdateEmbeddableSchemaInterface`](./src/SchemaUpdater/UpdateEmbeddableSchemaInterface.php)

Then, register your service and tag it as follows:

```yaml
tags:
  - { name: 'doctrine.migrations.schema_updater.embeddable_schema_updater' }
```

---

### 3. Implementing Custom Schema Updaters

For more advanced schema modification logic, you can create your own schema updater by implementing:

[`SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\SchemaUpdaterInterface`](./src/SchemaUpdater/SchemaUpdaterInterface.php)

and tagging it as:

```yaml
tags:
  - { name: 'doctrine.migrations.schema_updater.schema_updater' }
```

This allows you to define complex schema transformations that will be automatically included in the Doctrine migration diff.

---

### 4. Further Configuration

For detailed configuration options and customization possibilities, refer to the compiler pass and bundle configuration
[`services.yaml`](./src/Symfony/Resources/config/services.yaml)

[`SunnyFlail\DoctrineMigrationSchemaUpdater\Symfony\DependencyInjection\CompilerPass\CustomConfigureDependencyFactoryPass`](./src/Symfony/DependencyInjection/CompilerPass/CustomConfigureDependencyFactoryPass.php)

---

✅ **Summary:**
- Integrates with Doctrine’s migration diff process.
- Preserves custom schema changes across migrations.
- Easily extendable via tagged schema updaters.  
