<?php

namespace App\Terminal\Commands;

use App\Terminal\TerminalCommand;

class CertificationsCommand implements TerminalCommand
{
    public function signature(): string
    {
        return 'certifications';
    }

    public function description(): string
    {
        return 'List certifications and notable badges.';
    }

    public function handle(string $input, array $context = []): array
    {
        return [
            'type' => 'lines',
            'title' => 'Certifications',
            'data' => [
                [
                    'variant' => 'list',
                    'items' => [
                        'AWS Certified Solutions Architect – Associate',
                        'Google Professional Machine Learning Engineer',
                        'Meta Spark XR Creator — Advanced',
                        'Scrum Alliance Certified ScrumMaster',
                    ],
                    'speed' => 18,
                ],
            ],
        ];
    }
}
