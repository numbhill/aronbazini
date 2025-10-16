<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;

class SkillsCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'skills';
    }

    public function description(): string
    {
        return 'Summarise technical toolkit.';
    }

    public function handle(string $input, array $context = []): array
    {
        return [
            'type' => 'table',
            'title' => 'Skill Matrix',
            'data' => [
                'headers' => ['Discipline', 'Highlights'],
                'rows' => [
                    ['Laravel / PHP', 'Livewire v3, Volt, Octane, first-party packages, multi-tenancy'],
                    ['Frontend', 'TypeScript, React 18, Tailwind v4, R3F, accessibility-first UI systems'],
                    ['AI & Data', 'LangChain, OpenAI function calling, vector search, edge inference orchestration'],
                    ['DevOps', 'Docker, GitHub Actions, AWS (ECS, Lambda), Fly.io, PlanetScale, Railway'],
                    ['DX & Leadership', 'Docs-as-code, pairing, mentoring, async rituals, stakeholder storytelling'],
                ],
                'speed' => 18,
            ],
        ];
    }
}
