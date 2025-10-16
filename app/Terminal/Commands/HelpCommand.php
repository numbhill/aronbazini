<?php

namespace App\Terminal\Commands;

use App\Terminal\CommandRegistry;
use App\Terminal\RegistryAwareCommand;
use App\Terminal\TerminalCommand;

class HelpCommand implements TerminalCommand, RegistryAwareCommand
{
    protected CommandRegistry $registry;

    public function signature(): string
    {
        return 'help';
    }

    public function description(): string
    {
        return 'List all available commands.';
    }

    public function setRegistry(CommandRegistry $registry): void
    {
        $this->registry = $registry;
    }

    public function handle(string $input, array $context = []): array
    {
        $rows = [];

        foreach ($this->registry->all() as $command) {
            $rows[] = [
                'command' => $command->signature(),
                'description' => $command->description(),
            ];
        }

        return [
            'type' => 'table',
            'title' => 'Space Ranger Command Index',
            'data' => [
                'headers' => ['Command', 'Description'],
                'rows' => $rows,
                'speed' => 22,
            ],
        ];
    }
}
