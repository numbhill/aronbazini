<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;

class EducationCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'education';
    }

    public function description(): string
    {
        return 'Highlight academic background.';
    }

    public function handle(string $input, array $context = []): array
    {
        return [
            'type' => 'lines',
            'title' => 'Education',
            'data' => [
                [
                    'variant' => 'heading',
                    'text' => 'BSc. Computer Science — University of Nairobi',
                    'speed' => 24,
                ],
                [
                    'variant' => 'text',
                    'text' => 'Focused on distributed systems, computational geometry, and ML production pipelines. Led robotics club and organized Nairobi DevFest.',
                    'speed' => 20,
                ],
                [
                    'variant' => 'list',
                    'items' => [
                        'Research: Swarm navigation with reinforcement learning.',
                        'Capstone: Mixed-reality maintenance assistant for CubeSats.',
                    ],
                    'speed' => 20,
                ],
            ],
        ];
    }
}
