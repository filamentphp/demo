# Local Livewire lifecycle fix

This independent patch targets Livewire v3.8.8 at
[`ec19e5f`](https://github.com/livewire/livewire/commit/ec19e5fb1e0b60df22ad15b901c2f7740267ba11).
It is not an upstream release. No Livewire PHP or Inertia changes are needed for
this race fix. Database notifications must remain lazy in the demo.

From the demo, run `bash tools/livewire/prepare.sh`. In the adjacent `../livewire`
checkout, run `npm ci` followed by `npm run build`. Install the built `dist/`
assets into the runtime's Livewire package (or Composer-path-link this checkout
as Livewire 3.8.8). Preserve the demo's existing Filament Composer path links.
Verify the actual resolved Livewire class path and package version before
installing assets; do not assume the normal demo manifest describes the orb.
If Livewire assets are published, republish them with
`php artisan livewire:publish --assets` so the browser receives the patched build.

The patch checks the component object that owns the root element, rather than
only its `wire:id`. Navigation cleanup removes that ownership, and a restored
page may create a different object with the same ID.

- A successful response for a destroyed instance runs `respond` cleanup but
  skips snapshot/state merging, effects, commit finishers, and `succeed` hooks.
- Method-call promises still resolve with the server's return values, and
  commit promises still resolve. This does not cancel or undo server actions.
  Caller-provided promise continuations still execute.
- Each pooled response is consumed normally; surviving sibling commits retain
  their corresponding responses and promises.
- A queued HTML morph checks ownership again immediately before execution.
- Form-disabled cleanup uses Livewire's existing `WeakBag` keyed by component
  object, so an old response cannot enable a new instance's submitting form.

Reverse the previous revision's patch before applying an updated patch. The
preparation script never resets an incompatible checkout. Rebuild and reinstall
assets after every patch revision. The change does not cancel HTTP requests or
change request-wide redirects, errors, or asset loading.

## Regression tests

From the demo, run the dependency-free source tests:

```sh
node --experimental-vm-modules --test tools/livewire/tests/lifecycle.test.mjs
```

These load the actual Livewire commit, pool, hooks, utilities, and feature modules,
stubbing browser and scheduling boundaries. `LIVEWIRE_DIRECTORY` can select a
separate unpatched checkout for a negative control. The patched source passes all
nine cases; unpatched v3.8.8 passes the normal-response control and fails the other
eight.

Run the browser regression from the Filament checkout, where Playwright is
installed, against a disposable running demo with SPA and lazy notifications
enabled:

```sh
INERTIA_DEMO_URL=http://127.0.0.1:8000 node ../repos/demo/tools/livewire/tests/browser.mjs
```

Use an isolated test database and session cookie, and `CACHE_DRIVER=array` to
avoid login throttling across automated cases. The harness holds HTTP responses
after server processing, then releases them after navigation or component
destruction. It checks object generations, cleanup, suppressed effects, restored
lazy loading, and surviving pooled commits. It appends observable dispatch
effects to test responses without changing snapshots or method return values.
All three browser cases pass with patched assets and fail with upstream assets.
`CASES=away,restored,bundle` selects cases explicitly.
