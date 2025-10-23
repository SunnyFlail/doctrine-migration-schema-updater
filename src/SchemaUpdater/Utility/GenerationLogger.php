<?php

declare(strict_types=1);

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\Utility;

use Psr\Log\LoggerInterface;

final class GenerationLogger
{
    use GenerationLoggerIOTrait;

    public function __construct(
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function addLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function debug(string $message, array $context): void
    {
        $this->logger?->debug($message, $context);
        $this->io?->block(
            messages: $this->prepareConsoleMessage($message, $context),
            type: 'DEBUG',
            style: 'fg=black;bg=yellow',
            padding: true,
        );
    }

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context): void
    {
        $this->logger?->info($message, $context);
        $this->io?->info($this->prepareConsoleMessage($message, $context));
    }
}
