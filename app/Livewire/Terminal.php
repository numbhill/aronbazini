<?php

namespace App\Livewire;

use App\Terminal\CommandRegistry;
use App\Terminal\TerminalCommand;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class Terminal extends Component
{
    public string $input = '';

    /**
     * @var list<string>
     */
    public array $history = [];

    /**
     * @var list<array<string, mixed>>
     */
    public array $output = [];

    public int $cursorIndex = 0;

    public bool $isProcessing = false;

    /**
     * @var list<string>
     */
    public array $suggestions = [];

    public string $theme = 'dark';

    protected CommandRegistry $registry;

    public function mount(CommandRegistry $registry): void
    {
        $this->registry = $registry;
        $this->bootTerminal();
    }

    public function runCommand(): void
    {
        if ($this->isProcessing) {
            return;
        }

        $commandInput = trim($this->input);

        if ($commandInput === '') {
            $this->input = '';

            return;
        }

        $this->isProcessing = true;
        $this->suggestions = [];

        $this->history[] = $commandInput;
        $this->history = array_slice($this->history, -200);
        $this->cursorIndex = count($this->history);

        [$commandName, $arguments] = $this->splitCommand($commandInput);

        $entryId = (string) Str::uuid();

        $this->output[] = [
            'id' => $entryId,
            'command' => $commandName,
            'prompt' => $commandInput,
            'payload' => null,
            'status' => 'pending',
        ];

        $this->trimOutput();
        $this->dispatch('terminal-output-updated');

        $command = $this->registry->resolve($commandName);

        if (! $command instanceof TerminalCommand) {
            $this->finaliseEntry($entryId, $this->unknownCommandPayload($commandName));
            $this->finishCommand();

            return;
        }

        $payload = $command->handle($commandInput, [
            'arguments' => $arguments,
            'history' => $this->history,
            'theme' => $this->theme,
        ]);

        $this->processPayloadEffects($entryId, $payload);
        $this->finaliseEntry($entryId, $payload);
        $this->finishCommand();
    }

    public function autocomplete(): void
    {
        $seed = strtolower(trim($this->input));

        if ($seed === '') {
            return;
        }

        $matches = [];

        foreach (array_keys($this->registry->all()) as $command) {
            if (str_starts_with($command, $seed)) {
                $matches[] = $command;
            }
        }

        foreach ($this->registry->aliases() as $alias => $target) {
            if (str_starts_with($alias, $seed)) {
                $matches[] = $target;
            }
        }

        $matches = array_values(array_unique($matches));

        if (count($matches) === 1) {
            $this->input = $matches[0];
            $this->suggestions = [];

            return;
        }

        $this->suggestions = array_slice($matches, 0, 5);
    }

    public function historyUp(): void
    {
        if ($this->history === []) {
            return;
        }

        $this->cursorIndex = max(0, $this->cursorIndex - 1);
        $this->input = $this->history[$this->cursorIndex] ?? $this->input;
    }

    public function historyDown(): void
    {
        if ($this->history === []) {
            return;
        }

        $this->cursorIndex = min(count($this->history), $this->cursorIndex + 1);
        $this->input = $this->history[$this->cursorIndex] ?? '';
    }

    public function clear(): void
    {
        $this->output = [];
        $this->dispatch('terminal-output-updated');
    }

    public function render(): View
    {
        return view('livewire.terminal');
    }

    protected function bootTerminal(): void
    {
        $this->output = [
            [
                'id' => (string) Str::uuid(),
                'command' => 'boot',
                'prompt' => 'boot',
                'status' => 'complete',
                'payload' => [
                    'type' => 'lines',
                    'title' => 'Buzz Lightyear Systems Online',
                    'data' => [
                        [
                            'variant' => 'heading',
                            'text' => 'Welcome aboard, Space Ranger.',
                            'speed' => 30,
                        ],
                        [
                            'variant' => 'text',
                            'text' => 'Type `help` to discover available commands or `projects` to scan the launch bay.',
                            'speed' => 22,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function processPayloadEffects(string $entryId, array $payload): void
    {
        if (($payload['type'] ?? null) === 'clear') {
            $this->output = array_values(array_filter($this->output, fn (array $entry): bool => ($entry['id'] ?? null) === $entryId));
        }

        if (($payload['type'] ?? null) === 'theme') {
            $mode = $payload['data']['mode'] ?? 'dark';
            $this->theme = $mode;
            $this->dispatch('terminal-theme-changed', mode: $mode);
        }
    }

    protected function finishCommand(): void
    {
        $this->input = '';
        $this->isProcessing = false;
        $this->dispatch('terminal-output-updated');
    }

    protected function trimOutput(): void
    {
        $this->output = array_slice($this->output, -500);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function finaliseEntry(string $entryId, array $payload): void
    {
        foreach ($this->output as &$entry) {
            if (($entry['id'] ?? null) === $entryId) {
                $entry['payload'] = $payload;
                $entry['status'] = 'complete';
                break;
            }
        }

        unset($entry);
        $this->dispatch('terminal-output-updated');
    }

    /**
     * @return array{0: string, 1: list<string>}
     */
    protected function splitCommand(string $input): array
    {
        $segments = preg_split('/\s+/', trim($input)) ?: [];

        $command = strtolower(array_shift($segments) ?? '');

        return [$command, array_values($segments)];
    }

    /**
     * @return array<string, mixed>
     */
    protected function unknownCommandPayload(string $command): array
    {
        $suggestions = $this->registry->suggest($command);

        return [
            'type' => 'error',
            'title' => sprintf('Command "%s" not found', $command),
            'message' => 'Type `help` to review valid commands.',
            'suggestions' => $suggestions,
            'speed' => 18,
        ];
    }
}
