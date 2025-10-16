<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;
use Illuminate\Support\Arr;

class HistoryCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'history';
    }

    public function description(): string
    {
        return 'Show recently executed commands.';
    }

    public function handle(string $input, array $context = []): array
    {
        $history = array_values(array_unique(Arr::get($context, 'history', [])));

        return [
            'type' => 'lines',
            'title' => 'Recent Commands',
            'data' => [
                [
                    'variant' => 'list',
                    'items' => $history === [] ? ['No commands issued yet.'] : $history,
                    'speed' => 20,
                ],
            ],
        ];
    }
}
