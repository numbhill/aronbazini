<?php

namespace App\Terminal;

interface TerminalCommand
{
    public function signature(): string;

    public function description(): string;

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    public function handle(string $input, array $context = []): array;
}

interface RegistryAwareCommand
{
    public function setRegistry(CommandRegistry $registry): void;
}
