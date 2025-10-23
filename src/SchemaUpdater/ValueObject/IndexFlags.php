<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject;

final readonly class IndexFlags
{
    public function __construct(
        public bool $fulltext = false,
        public bool $spatial = false,
        public bool $clustered = false,
        public bool $nonClustered = false,
        public bool $hash = false,
        public bool $btree = false,
        public bool $gin = false,
        public bool $gist = false,
    ) {
    }

    public function toArray(): array
    {
        $flags = [];

        foreach (get_object_vars($this) as $flagName => $flagShouldBeAdded) {
            if (true === $flagShouldBeAdded) {
                $flags[] = $flagName;
            }
        }

        return $flags;
    }
}