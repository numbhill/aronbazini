<?php

use App\Livewire\Terminal;
use Livewire\Livewire;

it('executes help command and returns structured payload', function () {
    Livewire::test(Terminal::class)
        ->set('input', 'help')
        ->call('runCommand')
        ->assertSet('input', '')
        ->assertSee('Space Ranger Command Index');
});

it('returns suggestions for unknown commands', function () {
    Livewire::test(Terminal::class)
        ->set('input', 'helo')
        ->call('runCommand')
        ->assertSee('Command "helo" not found')
        ->assertSee('help');
});

it('clears output when clear command is issued', function () {
    Livewire::test(Terminal::class)
        ->set('input', 'about')
        ->call('runCommand')
        ->set('input', 'clear')
        ->call('runCommand')
        ->assertSet('output.0.command', 'clear');
});
