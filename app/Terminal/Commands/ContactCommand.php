<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;

class ContactCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'contact';
    }

    public function description(): string
    {
        return 'Share preferred communication channels.';
    }

    public function handle(string $input, array $context = []): array
    {
        return [
            'type' => 'lines',
            'title' => 'Contact',
            'data' => [
                [
                    'variant' => 'text',
                    'text' => 'Drop a transmission via the channels below — transmissions answered within 1 galactic day.',
                    'speed' => 20,
                ],
                [
                    'variant' => 'links',
                    'items' => [
                        ['label' => 'Email', 'href' => 'mailto:mark@buzzengineers.dev'],
                        ['label' => 'GitHub', 'href' => 'https://github.com/aronbazini'],
                        ['label' => 'LinkedIn', 'href' => 'https://www.linkedin.com/in/markgatero'],
                        ['label' => 'Calendar', 'href' => 'https://cal.com/mark-gatero/30'],
                    ],
                    'speed' => 18,
                ],
            ],
        ];
    }
}
