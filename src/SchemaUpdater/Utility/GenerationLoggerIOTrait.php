<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use Symfony\Component\Console\Style\SymfonyStyle;

if (true === class_exists(SymfonyStyle::class)) {
    trait GenerationLoggerIOTrait
    {
        private ?SymfonyStyle $io = null;

        public function addIO(SymfonyStyle $io): self
        {
            $this->io = $io;

            return $this;
        }

        /**
         * @param array<string, mixed> $context
         *
         * @return list<string>
         */
        private function prepareConsoleMessage(string $message, array $context): array
        {
            return [
                $message,
                sprintf('Context: %s', json_encode($context, JSON_PRETTY_PRINT)),
            ];
        }
    }
} else {
    trait GenerationLoggerIOTrait
    {
        private null $io = null;

        public function addIO(mixed $io): self
        {
            $this->io = null;

            return $this;
        }

        private function prepareConsoleMessage(string $message, array $context): array
        {
            return [];
        }
    }
}
