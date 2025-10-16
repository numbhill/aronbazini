<?php

use App\Livewire\Terminal;
use function Livewire\Volt\uses;

uses(Terminal::class);
?>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buzz Terminal — Mark Gatero</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/js/app.ts', 'resources/js/react/app.tsx'])
</head>
<body class="min-h-screen bg-[#03010d] text-white antialiased" data-terminal-focus-anchor>
<div class="relative flex min-h-screen flex-col overflow-hidden">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,#1f0a3e_0%,transparent_55%)] opacity-70"></div>
    <div class="pointer-events-none absolute inset-0 bg-[length:4px_4px] bg-[repeating-linear-gradient(0deg,rgba(117,255,227,0.08),rgba(117,255,227,0.08)_1px,transparent_1px,transparent_4px)] mix-blend-screen opacity-60"></div>
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(180deg,rgba(0,0,0,0)_0%,rgba(0,0,0,0.65)_100%)]"></div>

    <main class="relative z-10 flex flex-1 flex-col px-4 py-8 md:px-10">
        <header class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-[#5dffd3]">Buzz Ranger Interface</p>
                <h1 class="text-2xl font-semibold tracking-widest text-white">Mark Gatero // Software & ML Engineer</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-xs text-white/60">
                <span class="rounded border border-white/20 px-3 py-1 tracking-[0.3em] uppercase">Theme: {{ strtoupper($theme) }}</span>
                <button type="button" class="rounded border border-[#5dffd3]/40 px-3 py-1 text-[#5dffd3] transition hover:bg-[#5dffd3]/10" wire:click="runCommand" data-terminal-focus-anchor>
                    Run
                </button>
                <button type="button" class="rounded border border-white/20 px-3 py-1 transition hover:bg-white/10" wire:click="clear">Clear</button>
            </div>
        </header>

        <div class="grid flex-1 gap-6 lg:grid-cols-[minmax(320px,360px)_1fr] xl:grid-cols-[minmax(360px,420px)_1fr]">
            <aside class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                <h2 class="mb-3 text-sm uppercase tracking-[0.4em] text-[#5dffd3]">Profile Badge</h2>
                <div id="profile-badge" class="h-[420px] w-full overflow-hidden rounded-xl border border-[#5dffd3]/30 bg-black/40 shadow-[0_0_60px_rgba(93,255,211,0.25)]"></div>
                <p class="mt-4 text-xs text-white/60">3D badge loads when in view. If unavailable, a fallback will render automatically.</p>
            </aside>

            <section class="flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-black/60 p-6 backdrop-blur" role="region" aria-label="Interactive terminal">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.3em] text-[#b389ff]">Terminal feed</p>
                    <span class="text-xs text-white/50">History depth: {{ count($history) }}</span>
                </div>

                <div class="flex-1 overflow-y-auto rounded-xl border border-white/5 bg-black/40 p-4" id="terminal-scroll" aria-live="polite">
                    <div class="flex flex-col gap-6">
                        @foreach ($output as $entry)
                            <article class="flex flex-col gap-3 rounded-lg border border-white/5 bg-black/40 p-4 shadow-[0_0_35px_rgba(93,255,211,0.12)]">
                                @if ($entry['command'] !== 'boot')
                                    <div class="flex items-start gap-3 text-sm text-[#5dffd3]">
                                        <span class="font-mono text-[#9b8cff]">space-ranger@buzz:~$</span>
                                        <span class="flex-1 font-mono" data-typewriter data-typewriter-speed="28" data-typewriter-text="{{ e($entry['prompt']) }}">{{ $entry['prompt'] }}</span>
                                    </div>
                                @endif

                                @php($payload = $entry['payload'] ?? [])

                                @if (($payload['type'] ?? null) === 'lines')
                                    <div class="flex flex-col gap-2">
                                        @foreach ($payload['data'] ?? [] as $line)
                                            @if (($line['variant'] ?? null) === 'heading')
                                                <h3 class="font-mono text-lg uppercase tracking-[0.35em] text-[#5dffd3]" data-typewriter data-typewriter-speed="{{ $line['speed'] ?? 24 }}" data-typewriter-text="{{ e($line['text'] ?? '') }}">{{ $line['text'] ?? '' }}</h3>
                                            @elseif (($line['variant'] ?? null) === 'text')
                                                <p class="text-sm text-white/80" data-typewriter data-typewriter-speed="{{ $line['speed'] ?? 22 }}" data-typewriter-text="{{ e($line['text'] ?? '') }}">{{ $line['text'] ?? '' }}</p>
                                            @elseif (($line['variant'] ?? null) === 'list')
                                                <ul class="ml-6 list-disc space-y-1 text-sm text-white/70">
                                                    @foreach ($line['items'] ?? [] as $item)
                                                        <li data-typewriter data-typewriter-speed="{{ $line['speed'] ?? 22 }}" data-typewriter-text="{{ e($item) }}">{{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                            @elseif (($line['variant'] ?? null) === 'links')
                                                <ul class="flex flex-wrap gap-3">
                                                    @foreach ($line['items'] ?? [] as $link)
                                                        <li>
                                                            <a href="{{ $link['href'] ?? '#' }}" class="inline-flex items-center gap-2 rounded border border-[#5dffd3]/50 px-3 py-1 text-xs uppercase tracking-[0.3em] text-[#5dffd3] transition hover:bg-[#5dffd3]/10" target="_blank" rel="noreferrer">{{ $link['label'] ?? 'Link' }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif (($payload['type'] ?? null) === 'table')
                                    <div class="overflow-hidden rounded-lg border border-white/10">
                                        <table class="min-w-full divide-y divide-white/10 text-sm">
                                            <thead class="bg-white/10 text-[#5dffd3]">
                                            <tr>
                                                @foreach (($payload['data']['headers'] ?? []) as $header)
                                                    <th scope="col" class="px-4 py-2 text-left font-mono uppercase tracking-[0.3em] text-xs">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-white/5">
                                            @foreach (($payload['data']['rows'] ?? []) as $row)
                                                <tr class="text-white/80">
                                                    @foreach ($row as $cell)
                                                        <td class="px-4 py-2 align-top" data-typewriter data-typewriter-speed="{{ $payload['data']['speed'] ?? 18 }}" data-typewriter-text="{{ e($cell) }}">{{ $cell }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif (($payload['type'] ?? null) === 'rich')
                                    <div class="grid gap-4 md:grid-cols-2">
                                        @foreach (($payload['data']['items'] ?? []) as $item)
                                            <div class="rounded-lg border border-[#5dffd3]/20 bg-black/60 p-4">
                                                <h3 class="font-mono text-sm uppercase tracking-[0.4em] text-[#5dffd3]" data-typewriter data-typewriter-speed="{{ $payload['data']['speed'] ?? 20 }}" data-typewriter-text="{{ e($item['title'] ?? '') }}">{{ $item['title'] ?? '' }}</h3>
                                                <p class="mt-1 text-xs uppercase tracking-[0.35em] text-white/60">{{ $item['subtitle'] ?? '' }}</p>
                                                <p class="mt-3 text-sm text-white/75" data-typewriter data-typewriter-speed="{{ $payload['data']['speed'] ?? 20 }}" data-typewriter-text="{{ e($item['description'] ?? '') }}">{{ $item['description'] ?? '' }}</p>
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach ($item['links'] ?? [] as $link)
                                                        <a href="{{ $link['href'] ?? '#' }}" class="inline-flex items-center gap-2 rounded-full border border-[#b389ff]/50 px-3 py-1 text-xs uppercase tracking-[0.3em] text-[#b389ff] transition hover:bg-[#b389ff]/10" target="_blank" rel="noreferrer">{{ $link['label'] ?? 'Open' }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif (($payload['type'] ?? null) === 'error')
                                    <div class="rounded border border-orange-400/60 bg-orange-500/10 p-4 text-sm text-orange-200">
                                        <p class="font-semibold uppercase tracking-[0.35em]">{{ $payload['title'] ?? 'Error' }}</p>
                                        <p class="mt-2" data-typewriter data-typewriter-speed="{{ $payload['speed'] ?? 18 }}" data-typewriter-text="{{ e($payload['message'] ?? '') }}">{{ $payload['message'] ?? '' }}</p>
                                        @if (! empty($payload['suggestions'] ?? []))
                                            <p class="mt-2 text-xs uppercase tracking-[0.35em] text-orange-200/70">Maybe you meant:</p>
                                            <ul class="mt-1 flex flex-wrap gap-2 text-xs">
                                                @foreach ($payload['suggestions'] as $suggestion)
                                                    <li class="rounded border border-orange-200/40 px-2 py-1 text-orange-200/80">{{ $suggestion }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @elseif (($payload['type'] ?? null) === 'theme')
                                    <div class="flex items-center gap-3 rounded border border-[#5dffd3]/40 bg-[#5dffd3]/10 px-4 py-3 text-sm text-[#5dffd3]">
                                        <span class="font-mono uppercase tracking-[0.4em]">{{ strtoupper($payload['data']['mode'] ?? 'dark') }}</span>
                                        <p data-typewriter data-typewriter-speed="{{ $payload['data']['speed'] ?? 18 }}" data-typewriter-text="{{ e($payload['data']['message'] ?? '') }}">{{ $payload['data']['message'] ?? '' }}</p>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>

                <form wire:submit.prevent="runCommand" class="mt-6">
                    <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-black/60 px-4 py-3 shadow-[0_0_30px_rgba(179,137,255,0.2)]">
                        <span class="font-mono text-xs uppercase tracking-[0.4em] text-[#b389ff]">space-ranger@buzz:~$</span>
                        <input
                            autocomplete="off"
                            autofocus
                            wire:model.defer="input"
                            wire:keydown.arrow-up.prevent="historyUp"
                            wire:keydown.arrow-down.prevent="historyDown"
                            wire:keydown.tab.prevent="autocomplete"
                            class="flex-1 bg-transparent font-mono text-sm text-white outline-none placeholder:text-white/30"
                            placeholder="Type a command..."
                            aria-label="Command input"
                        />
                        <button type="submit" class="rounded border border-[#5dffd3]/40 px-3 py-1 text-xs uppercase tracking-[0.3em] text-[#5dffd3] transition hover:bg-[#5dffd3]/10">Send</button>
                    </div>
                    @if ($suggestions !== [])
                        <div class="mt-3 flex flex-wrap gap-2 text-xs text-white/60">
                            @foreach ($suggestions as $suggestion)
                                <span class="rounded-full border border-white/20 px-3 py-1 font-mono text-[#5dffd3]">{{ $suggestion }}</span>
                            @endforeach
                        </div>
                    @endif
                </form>
            </section>
        </div>
    </main>
</div>
</body>
</html>
