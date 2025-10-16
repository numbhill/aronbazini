<?php

use function Pest\Browser\visit;

it('renders the terminal and interacts without console errors', function () {
    $page = visit('/');

    $page->assertSee('Buzz Ranger Interface')
        ->assertNoJavascriptErrors()
        ->fill('Command input', 'help')
        ->press('Send')
        ->waitForText('Space Ranger Command Index')
        ->fill('Command input', 'projects')
        ->press('Send')
        ->waitForText('Launch Bay Projects')
        ->fill('Command input', 'history')
        ->press('Send')
        ->waitForText('Recent Commands')
        ->runScript("document.dispatchEvent(new CustomEvent('terminal:toggle-reduced-motion', { detail: { enabled: true } }));")
        ->assertScript("document.documentElement.dataset.reducedMotion === 'true'");
});
