type ReactDomClientModule = typeof import('react-dom/client');
type ReactModule = typeof import('react');
type ReactFunctionComponent = ReactModule['FC'];

const mountReactBadge = async (host: HTMLElement): Promise<void> => {
    const [{ createRoot }, React] = (await Promise.all([
        import('react-dom/client'),
        import('react'),
    ])) as [ReactDomClientModule, ReactModule];

    const { Suspense, useEffect, useMemo, useState } = React;
    const LazyProfileBadge3D = React.lazy(() => import('./profile-badge-3D'));

    const isWebGLAvailable = (): boolean => {
        try {
            const canvas = document.createElement('canvas');
            return Boolean(canvas.getContext('webgl') ?? canvas.getContext('experimental-webgl'));
        } catch (error) {
            return false;
        }
    };

    const FallbackArtwork: ReactFunctionComponent = () => (
        <div className="flex h-full w-full flex-col items-center justify-center gap-4 rounded-xl border border-white/10 bg-black/40 p-6 text-center text-white/80 shadow-[0_0_25px_rgba(93,255,211,0.25)]">
            <svg
                className="h-16 w-16 text-[#5dffd3]"
                viewBox="0 0 64 64"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M32 4l8 16 18 2-13 12 3 18-16-9-16 9 3-18-13-12 18-2 8-16z"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinejoin="round"
                    strokeLinecap="round"
                />
            </svg>
            <div className="space-y-2">
                <p className="text-lg font-semibold tracking-wide">Enable WebGL to launch the 3D badge.</p>
                <p className="text-sm leading-relaxed text-white/60">
                    We detected that WebGL is not available. Please enable hardware acceleration or visit this portfolio from a modern browser.
                </p>
            </div>
        </div>
    );

    const BadgeContainer: ReactFunctionComponent = () => {
        const [isWebGLReady, setWebGLReady] = useState(false);
        const [assetError, setAssetError] = useState<string | null>(null);

        const webglSupported = useMemo(() => isWebGLAvailable(), []);

        useEffect(() => {
            if (webglSupported) {
                requestAnimationFrame(() => setWebGLReady(true));
            }
        }, [webglSupported]);

        if (!webglSupported) {
            return <FallbackArtwork />;
        }

        if (assetError) {
            return (
                <div className="flex h-full w-full flex-col items-center justify-center gap-3 rounded-xl border border-orange-400/50 bg-black/40 p-6 text-center text-orange-100">
                    <h3 className="text-lg font-semibold uppercase tracking-[0.4em]">Telemetry Notice</h3>
                    <p className="text-sm leading-relaxed text-orange-200/80">
                        We were unable to load the 3D badge assets. Reload the page or check your connection.
                    </p>
                    <a
                        href="https://support.google.com/chrome/answer/8369774"
                        target="_blank"
                        rel="noreferrer"
                        className="text-sm font-medium text-[#5dffd3] underline decoration-dotted underline-offset-4"
                    >
                        Learn how to enable hardware acceleration
                    </a>
                </div>
            );
        }

        if (!isWebGLReady) {
            return (
                <div className="flex h-full w-full items-center justify-center rounded-xl border border-white/10 bg-black/40 p-6 text-[#5dffd3]">
                    <p className="animate-pulse text-sm tracking-[0.6em] uppercase">Calibrating badge...</p>
                </div>
            );
        }

        return (
            <Suspense
                fallback={
                    <div className="flex h-full w-full items-center justify-center rounded-xl border border-white/10 bg-black/40 p-6 text-[#5dffd3]">
                        <p className="animate-pulse text-sm tracking-[0.6em] uppercase">Initializing 3D systems...</p>
                    </div>
                }
            >
                <LazyProfileBadge3D onAssetError={setAssetError} />
            </Suspense>
        );
    };

    const root = createRoot(host);

    root.render(
        <React.StrictMode>
            <BadgeContainer />
        </React.StrictMode>,
    );
};

const boot = (): void => {
    const host = document.getElementById('profile-badge');

    if (!host || host.dataset.badgeMounted === 'true') {
        return;
    }

    const startMount = () => {
        host.dataset.badgeMounted = 'true';
        mountReactBadge(host).catch((error) => {
            console.error('Unable to initialize ProfileBadge3D', error);
        });
    };

    if (!('IntersectionObserver' in window)) {
        startMount();
        return;
    }

    const observer = new IntersectionObserver(
        (entries, entryObserver) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entryObserver.disconnect();
                    startMount();
                }
            });
        },
        { rootMargin: '25% 0px' },
    );

    observer.observe(host);
};

document.addEventListener('DOMContentLoaded', boot);
