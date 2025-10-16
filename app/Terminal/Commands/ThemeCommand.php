<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;
use Illuminate\Support\Arr;

class ThemeCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'theme';
    }

    public function description(): string
    {
        return 'Switch between Synthwave theme presets.';
    }

    public function handle(string $input, array $context = []): array
    {
        $arguments = Arr::get($context, 'arguments', []);
        $mode = $arguments[0] ?? 'dark';

        $available = ['dark', 'light', 'buzz'];

        if (! in_array($mode, $available, true)) {
            return [
                'type' => 'error',
                'title' => 'Unknown theme',
                'message' => sprintf('Unsupported theme "%s". Try: %s.', $mode, implode(', ', $available)),
                'speed' => 20,
            ];
        }

        return [
            'type' => 'theme',
            'title' => 'Theme Updated',
            'data' => [
                'mode' => $mode,
                'message' => sprintf('Engaging %s drive.', ucfirst($mode)),
                'speed' => 18,
            ],
        ];
    }
}
