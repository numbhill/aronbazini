<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;

class ClearCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'clear';
    }

    public function description(): string
    {
        return 'Clear the terminal viewport.';
    }

    public function handle(string $input, array $context = []): array
    {
        return [
            'type' => 'clear',
            'title' => 'Clearing viewport',
            'data' => [
                'message' => 'Wiping the nav console...',
            ],
        ];
    }
}
