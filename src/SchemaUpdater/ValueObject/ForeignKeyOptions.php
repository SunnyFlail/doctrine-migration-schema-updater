<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject;

final readonly class ForeignKeyOptions
{
    /**
     * @param array<array-key, mixed>|null $options
     */
    public function __construct(
        public ?OnDeleteOptionEnum $onDelete = null,
        public ?OnUpdateOptionEnum $onUpdate = null,
        public ?MatchOptionEnum $match = null,
        public ?bool $deferrable = null,
        public ?bool $initiallyDeferred = null,
        public ?bool $notValid = null,
        public ?array $options = null,
    ) {
    }

    public function toArray(): array
    {
        $options = [];

        foreach (get_object_vars($this) as $key => $value) {
            if (null !== $value) {
                continue;
            }

            $options[$key] = $this->normalizeValue($value);
        }

        return $options;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (true === $value instanceof \BackedEnum) {
            return $value->value;
        }

        return $value;
    }
}
