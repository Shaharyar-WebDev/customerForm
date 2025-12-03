document.addEventListener("DOMContentLoaded", function () {
    // Wait a bit for Livewire to initialize
    setTimeout(() => {
        if (window.NProgress) {
            const originalStart = window.NProgress.start;
            const originalDone = window.NProgress.done;

            let startTime = null;
            let target = 0;
            let animating = false;
            let doneTimeout = null;

            function animateProgress() {
                if (!window.NProgress || !animating) return;

                const current = window.NProgress.status || 0;
                const delta = (target - current) * 0.1; // easing factor
                const next = current + delta;

                window.NProgress.set(next);

                if (Math.abs(target - next) > 0.001) {
                    requestAnimationFrame(animateProgress);
                } else {
                    animating = false;
                }
            }

            window.NProgress.start = function () {
                startTime = Date.now();
                target = 0.95;
                animating = true;

                // Reset bar if it was already completed
                if (window.NProgress.status >= 1) {
                    window.NProgress.set(0);
                }

                // Clear any pending done timeout from previous navigation
                if (doneTimeout) {
                    clearTimeout(doneTimeout);
                    doneTimeout = null;
                }

                animateProgress();
                return originalStart.call(this);
            };

            window.NProgress.done = function (force) {
                if (doneTimeout) {
                    clearTimeout(doneTimeout);
                    doneTimeout = null;
                }

                if (force) {
                    animating = false;
                    return originalDone.call(this, true);
                }

                const elapsed = Date.now() - (startTime || Date.now());
                const minDuration = 500; // ensure minimum visible time
                const remainingTime = Math.max(minDuration - elapsed, 0);

                // Animate to 100% smoothly after minimum duration
                doneTimeout = setTimeout(() => {
                    target = 1.0;
                    animating = true;
                    animateProgress();

                    // Ensure original done is called after animation
                    doneTimeout = setTimeout(() => {
                        animating = false;
                        originalDone.call(this, true);
                    }, 250);
                }, remainingTime);

                return this;
            };
        }
    }, 100);

    if (!window.NProgress) {
        console.warn("failed to load NProgress.");
        return;
    } else {
        document.addEventListener("livewire:init", () => {
            Livewire.hook("commit.prepare", () => window.NProgress.start());
            Livewire.hook("commit", ({ succeed }) =>
                succeed(() => queueMicrotask(() => window.NProgress.done()))
            );
        });
    }
});
