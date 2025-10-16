<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;

class ProjectsCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'projects';
    }

    public function description(): string
    {
        return 'Show featured builds and case studies.';
    }

    public function handle(string $input, array $context = []): array
    {
        return [
            'type' => 'rich',
            'title' => 'Launch Bay Projects',
            'data' => [
                'items' => [
                    [
                        'title' => 'Astro Console',
                        'subtitle' => 'Realtime flight-ops command center',
                        'description' => 'Laravel + Livewire powered telemetry board delivering <150ms updates for drone fleets. Includes anomaly detection and mission replay pipelines.',
                        'links' => [
                            [
                                'label' => 'Case Study',
                                'href' => 'https://example.com/astro-console',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Nebula Forge',
                        'subtitle' => '3D configurator + CPQ engine',
                        'description' => 'React Three.js experience for modular satellites, layered with predictive pricing models and headless commerce.',
                        'links' => [
                            [
                                'label' => 'Demo',
                                'href' => 'https://example.com/nebula-forge',
                            ],
                        ],
                    ],
                    [
                        'title' => 'ChronoSync',
                        'subtitle' => 'Async engineering OS',
                        'description' => 'Synthwave-themed developer hub aligning CI/CD, feature flags, and observability into a single control surface.',
                        'links' => [
                            [
                                'label' => 'Launch Notes',
                                'href' => 'https://example.com/chronosync',
                            ],
                        ],
                    ],
                ],
                'speed' => 20,
            ],
        ];
    }
}
