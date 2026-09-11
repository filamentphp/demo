# Local Inertia dependency

This prototype links four npm packages from the adjacent `../inertia` checkout.
The patch is against upstream Inertia v3.7.0, commit
[`721eef6`](https://github.com/inertiajs/inertia/commit/721eef6464ac53c84b52da2f2898d048eea1efcb).
It includes core changes, all three adapters, and core regression tests. No
`inertia-laravel` source changes are required.

From this demo, prepare the source:

```sh
bash tools/inertia/prepare.sh
```

From `../inertia`, install the package workspace and build sequentially:

```sh
pnpm install --frozen-lockfile --ignore-scripts \
  --filter @inertiajs/core... --filter @inertiajs/vue3... \
  --filter @inertiajs/react... --filter @inertiajs/svelte...
pnpm --filter @inertiajs/core build
pnpm --filter @inertiajs/vue3 build
pnpm --filter @inertiajs/react build
pnpm --filter @inertiajs/svelte exec svelte-package --input src
pnpm --filter @inertiajs/core test
```

Then, from this demo:

```sh
npm ci --install-links=false
npm run build:ssr
```

Svelte packaging is separate from its full repository checks, which also load
the upstream test application's configuration and dependencies. Run those checks
in a fully installed Inertia workspace before proposing upstream changes.

When testing a new demo revision, reverse the **previous revision's** patch from
the adjacent Inertia checkout before applying the new one. Do not discard unrelated
local changes. The preparation script fails rather than resetting an incompatible
checkout. Rebuild the packages and the demo, and restart the SSR service after
each revision. Record the demo commit in every verification report.

The demo's normal branch declares Filament 5, but this prototype has been exercised
with local Composer path links to Filament 4.x. Reproduce those links and verify the
resolved class paths; the manifest alone does not identify the runtime under test.
