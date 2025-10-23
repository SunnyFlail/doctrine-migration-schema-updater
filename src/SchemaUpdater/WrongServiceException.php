<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater;

class WrongServiceException extends \RuntimeException
{
    /**
     * @param class-string $expectedClassFQCN
     */
    public function __construct(
        string $template,
        public readonly string $expectedClassFQCN,
        public readonly string $actualType,
    ) {
        parent::__construct(sprintf($template, $expectedClassFQCN, $actualType));
    }

    public static function createForSchemaUpdater(mixed $receivedService): self {
        return new self(
            'Schema updater must implement "%s". Got %s instead.',
            SchemaUpdaterInterface::class,
            true === is_object($receivedService)
                ? get_class($receivedService)
                : gettype($receivedService),
        );
    }

    public static function createForEmbeddableSchemaUpdater(mixed $receivedService): self {
        return new self(
            'Embeddable schema updater must implement "%s". Got %s instead.',
            EmbeddableSchemaUpdater::class,
            true === is_object($receivedService)
                ? get_class($receivedService)
                : gettype($receivedService),
        );
    }
}