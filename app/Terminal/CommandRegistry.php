<?php

namespace App\Terminal;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use function array_slice;
use function asort;
use function array_keys;
use function array_map;
use function array_unique;
use function levenshtein;
use function strtolower;

class CommandRegistry
{
    /**
     * @var array<string, TerminalCommand>
     */
    protected array $commands = [];

    /**
     * @var array<string, string>
     */
    protected array $aliases = [];

    public function __construct(protected Application $app)
    {
        $this->bootstrap();
    }

    /**
     * @return array<string, TerminalCommand>
     */
    public function all(): array
    {
        return $this->commands;
    }

    public function resolve(string $name): ?TerminalCommand
    {
        $key = strtolower($name);

        if (isset($this->aliases[$key])) {
            $key = $this->aliases[$key];
        }

        return $this->commands[$key] ?? null;
    }

    /**
     * @return list<string>
     */
    public function suggest(string $input, int $limit = 3): array
    {
        $needles = array_keys($this->commands + $this->aliases);

        $scores = [];

        foreach ($needles as $needle) {
            $scores[$needle] = levenshtein(strtolower($input), strtolower($needle));
        }

        asort($scores);

        $suggestions = array_slice(array_keys($scores), 0, $limit);

        return array_values(array_unique(array_map(function (string $suggestion): string {
            return $this->aliases[$suggestion] ?? $suggestion;
        }, $suggestions)));
    }

    /**
     * @param class-string<TerminalCommand> $command
     * @param list<string> $aliases
     */
    public function register(string $command, array $aliases = []): void
    {
        $instance = $this->app->make($command);

        if (!$instance instanceof TerminalCommand) {
            throw new InvalidArgumentException(sprintf('Command %s must implement %s.', $command, TerminalCommand::class));
        }

        if ($instance instanceof RegistryAwareCommand) {
            $instance->setRegistry($this);
        }

        $signature = strtolower($instance->signature());

        $this->commands[$signature] = $instance;

        foreach ($aliases as $alias) {
            $this->aliases[strtolower($alias)] = $signature;
        }
    }

    /**
     * @return array<string, string>
     */
    public function aliases(): array
    {
        return $this->aliases;
    }

    protected function bootstrap(): void
    {
        $this->register(Commands\HelpCommand::class, ['help', '?']);
        $this->register(Commands\AboutCommand::class, ['about', 'whoami']);
        $this->register(Commands\ProjectsCommand::class, ['projects', 'ls']);
        $this->register(Commands\SkillsCommand::class, ['skills']);
        $this->register(Commands\EducationCommand::class, ['education']);
        $this->register(Commands\CertificationsCommand::class, ['certifications']);
        $this->register(Commands\ThemeCommand::class, ['theme']);
        $this->register(Commands\ClearCommand::class, ['clear', 'cls']);
        $this->register(Commands\HistoryCommand::class, ['history']);
        $this->register(Commands\ContactCommand::class, ['contact']);
    }
}
