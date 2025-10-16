import '../css/app.css';
import './bootstrap';

import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

/**
 * Persists and toggles the reduced-motion preference for the terminal UI.
 */
const reducedMotionStorageKey = 'terminal:reduced-motion';
const reducedMotionToggleEvent = 'terminal:toggle-reduced-motion';
const typewriterSelector = '[data-typewriter]';

type ReducedMotionEvent = CustomEvent<{ enabled: boolean }>;

const applyReducedMotionPreference = (enabled: boolean): void => {
    if (enabled) {
        document.documentElement.dataset.reducedMotion = 'true';
    } else {
        document.documentElement.dataset.reducedMotion = 'false';
    }
};

const initialiseReducedMotionPreference = (): void => {
    const storedPreference = window.localStorage.getItem(reducedMotionStorageKey);

    if (storedPreference !== null) {
        applyReducedMotionPreference(storedPreference === 'true');
        return;
    }

    const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    applyReducedMotionPreference(mediaQuery.matches);

    mediaQuery.addEventListener('change', (event) => {
        applyReducedMotionPreference(event.matches);
    });
};

if (typeof window !== 'undefined') {
    initialiseReducedMotionPreference();

    document.addEventListener(reducedMotionToggleEvent, (event) => {
        const detail = (event as ReducedMotionEvent).detail;

        if (typeof detail?.enabled === 'boolean') {
            applyReducedMotionPreference(detail.enabled);
            window.localStorage.setItem(reducedMotionStorageKey, String(detail.enabled));
        }
    });
}

/**
 * Emits an event that Volt listens to in order to keep the terminal input focused.
 */
const focusInputEvent = new Event('terminal:focus-input');

const typewriterDelay = (speed: number): number => {
    return Math.max(8, Math.floor(1000 / speed));
};

const runTypewriter = (element: HTMLElement): void => {
    const existing = element.dataset.typewriterBound;

    if (existing === 'true') {
        return;
    }

    const text = element.dataset.typewriterText ?? element.textContent ?? '';
    const speed = Number(element.dataset.typewriterSpeed ?? '26');

    element.dataset.typewriterBound = 'true';

    if (document.documentElement.dataset.reducedMotion === 'true') {
        element.textContent = text;

        return;
    }

    element.textContent = '';

    let index = 0;

    const step = () => {
        index += 1;
        element.textContent = text.slice(0, index);

        if (index < text.length) {
            window.setTimeout(step, typewriterDelay(speed));
        }
    };

    step();
};

const hydrateTypewriterElements = (): void => {
    const elements = document.querySelectorAll<HTMLElement>(typewriterSelector);

    elements.forEach((element) => {
        if (! element.dataset.typewriterText) {
            element.dataset.typewriterText = element.textContent ?? '';
        }

        runTypewriter(element);
    });
};

if (typeof window !== 'undefined') {
    document.addEventListener('click', (event) => {
        const target = event.target as HTMLElement | null;

        if (target?.closest('[data-terminal-focus-anchor]')) {
            document.dispatchEvent(focusInputEvent);
        }
    });

    document.addEventListener('terminal:focus-input', () => {
        const input = document.querySelector<HTMLInputElement>('input[aria-label="Command input"]');
        input?.focus();
    });

    document.addEventListener('DOMContentLoaded', () => {
        requestAnimationFrame(hydrateTypewriterElements);
    });
}

Livewire.on('terminal-output-updated', () => {
    requestAnimationFrame(hydrateTypewriterElements);
});

Livewire.start();
