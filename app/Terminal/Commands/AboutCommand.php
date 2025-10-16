<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;

class AboutCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'about';
    }

    public function description(): string
    {
        return 'Display an overview of the engineer.';
    }

    public function handle(string $input, array $context = []): array
    {
        return [
            'type' => 'lines',
            'title' => 'About Mark Gatero',
            'data' => [
                [
                    'variant' => 'heading',
                    'text' => 'Mark Gatero — Software & ML Engineer',
                    'speed' => 28,
                ],
                [
                    'variant' => 'text',
                    'text' => 'Space Ranger caliber engineer blending Laravel craftsmanship, real-time UX, and 3D storytelling for mission-critical products.',
                    'speed' => 24,
                ],
                [
                    'variant' => 'text',
                    'text' => 'Previously shipped AI-driven developer tooling, HRI dashboards, and immersive digital twins for aerospace, fintech, and healthtech crews.',
                    'speed' => 22,
                ],
                [
                    'variant' => 'text',
                    'text' => 'Operates from Nairobi + Nairobi-orbit remote stations, collaborating asynchronously across EST, CET, and JST timezones.',
                    'speed' => 22,
                ],
                [
                    'variant' => 'list',
                    'items' => [
                        'Mission profile: Laravel, Livewire, TypeScript, React, and GPU-accelerated experiences.',
                        'Callsigns: #buildinpublic, #devrel, #mlops, #webxr.',
                        'Buzz mantra: “To infinity, and beyond the backlog.”',
                    ],
                    'speed' => 26,
                ],
            ],
        ];
    }
}
