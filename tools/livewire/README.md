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
