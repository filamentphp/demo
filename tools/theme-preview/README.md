# Optional theme preview

This directory belongs to the demo. Nothing runs automatically: the committed Composer manifest/lock, admin panel, `/app` panel and assets stay unchanged until the installer is explicitly invoked. No theme-manager checkout, sibling packages, copied proprietary source, Composer hooks or extra baseline dependencies are needed.

## Laravel Cloud build (published packages)

Configure Composer HTTP Basic authentication for `packages.filamentphp.com` in Cloud's secret configuration, using the licensed email/key described in the theme package READMEs. Supply it through `COMPOSER_AUTH` or Composer's external authentication configuration, never through tracked files or command-line arguments. The installer adds the documented `https://packages.filamentphp.com/composer` repository, preserving an existing equivalent entry. Normal published-package deployment does **not** require GitHub credentials.

Run in a fresh, disposable build checkout, before production caching/optimizations:

```sh
set -eu
composer install --no-dev --prefer-dist --no-interaction --no-scripts
node tools/theme-preview/install.mjs --production
npm ci
npm run build
# Only after successful installation and asset compilation:
php artisan optimize
```

Replace/reorder Cloud's existing build commands accordingly; do not run this on a serving release. The installer requires Node and Composer at build time, uses a restricted update of the four theme packages plus Sharp's Material Symbols dependency, retains the existing lockfile, and runs the demo's existing autoload/package-discovery scripts after activation. It never uses an unrestricted update or `--with-all-dependencies`. If locked dependencies are incompatible, the build fails rather than silently upgrading the application. Review a deliberate baseline dependency update separately.

The planned published major constraint is defined once as `themeVersion` in `install.mjs`. Published release metadata and final registry/Cloud authentication have **not** been verified: do a fresh authenticated build after publication before deploying. Preserve the resulting build lockfile in deployment artifacts for provenance; do not commit generated changes back into the normal demo.

## Temporary private-VCS test mode

Before publication, use the **same installer** with `--vcs` (optionally also `--production`). This replaces the Filament registry route with four private GitHub VCS repositories, using `1.x-dev` plus the reviewed commit IDs in `vcs-refs.json`. All four current default branches are `1.x`. Composer branch metadata still comes from the branch; a commit suffix is not a substitute for reviewing changed package metadata. Update reviewed refs explicitly, never auto-follow a branch in the installer.

```sh
# Set COMPOSER_AUTH securely outside this command with GitHub read access.
node tools/theme-preview/install.mjs --vcs
npm ci
npm run build
```

The VCS route needs GitHub read access to all four private repositories; registry credentials alone cannot authorize it. An actual authenticated Composer resolution **and installation** of all four reviewed commits, followed by the full installer including the icon dependency, was tested in scratch. This proves the temporary VCS path, not registry authentication or Cloud deployment. No credentials are written by the installer. Theme packages remain proprietary in `vendor/`; publish compiled preview assets only as permitted by their licenses.

## Repeatability and failure

Successful installation records generated-file hashes in `.theme-preview-install.json`. Repeating identical options verifies these hashes and uses `composer install` against the generated lock, without re-resolving versions. Changed files/options are refused; use a fresh build for updates. The installer validates the current admin/Vite source shapes and refuses conflicting generated files or symlinked output paths before mutation. It does not modify the reset command, schedule, or `/app` provider.

On a caught installation failure, source files and both Composer manifests are restored. Composer may already have changed `vendor/`, published assets or caches. **Never serve a failed build.** Prefer discarding it; for a disposable local retry, restore dependencies with `composer install`, clear caches with `php artisan optimize:clear`, and rerun. Abrupt process termination cannot guarantee rollback: discard an interrupted build. Do not remove the install marker to overwrite an installed/customized tree.

## Preview behavior

Fresh sessions use Stock without Compact, with “Switch theme” collapsed. Choose Stock, Sharp, Soft or Noir independently of Compact and Filament's light/dark preference: eight CSS hosts, sixteen appearance states. Only one host loads. CSS imports the installed `vendor/filament/*-theme` packages, with the layer declaration before Filament, then personality, then Compact.

The amber circular launcher reveals its label on mouse hover or keyboard focus. It opens a native browser popover containing theme cards and Filament's toggle, tabs, and icon-button components for Compact, Light/Dark, and Close. The theme packages style these controls; the tabs use a two-column utility grid. Opening/closing works even before the preview script loads; Escape and outside clicks also dismiss it. Native popover support is required (current Chrome, Firefox and Safari). Reduced-motion preferences suppress transitions, and the launcher stays clear of Amp's feedback bubble when present. Ordinary navigation and reloads leave the picker closed.

Stock retains Albert Sans/Blue. Soft uses its PHP palette, Albert Sans and Lora; Sharp uses Inter and its Material Symbols aliases; Noir uses Inter and its PHP palette. The preview uses sidebar navigation consistently. The `/app` panel remains stock without a toolbar.

The showroom follows the active theme's surface and radius tokens, with Compact spacing on desktop only. Its cards keep their own typography and visual identity. Inter is bundled from Filament; Albert Sans and Lora use the same Bunny Fonts service as the demo. Prices in the toolbar Blade template are advertised single-project USD prices ($39 per personality theme, $29 for Compact), not a live checkout quote; keep them aligned with the themes page when pricing changes. The pricing link opens `https://filamentphp.com/themes` in a new tab without interrupting the demo.

Selecting Sharp or Soft in the showroom enables Light mode and Compact; selecting Noir enables Dark mode and Compact. Selecting Default preserves the current settings. Manual appearance/density controls and direct links retain their explicit choices; cancelling a dirty-form prompt applies none of the new theme's presets.

Confirmed theme and Compact changes blur only the switcher popup behind a “Refreshing the demo…” spinner. Interaction is blocked until navigation completes, but the rest of the page stays visually unchanged. The popup stays open during switching and restores without an entry animation; manual opening/closing still animates. Browser Back restores usable controls, and reduced-motion preferences stop the spinner animation.

`?theme=sharp&compact=1` selects a shareable style. On the first visit in a session, a valid theme or Compact parameter also opens the picker, including through the root-to-login redirect. This opening hint is consumed when the picker renders, not saved with the theme choice. Switcher-initiated theme/density changes use a one-shot, tab-local reopening hint. Invalid inputs are ignored. Selection is session-backed and request-memoized, including Livewire requests, not stored on Octane's persistent panel. Style switches fully reload while preserving unrelated query parameters/fragments; ordinary navigation remains SPA. Unsaved input prompts before switching, and cancellation restores the controls. The floating toolbar overlays content without adding page padding; close it to access content beneath it.

## Verification and local Octane

```sh
# Baseline checkout; no theme dependencies needed:
node --test tools/theme-preview/install.test.mjs
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --compact tests/Filament/Pages/ShopDashboardTest.php

# After opt-in installation, with development dependencies:
php artisan test --compact tests/Feature/LiveDemoTest.php
php artisan octane:install --server=frankenphp --no-interaction
php artisan octane:start --server=frankenphp --workers=1 --max-requests=10000
```

Use a disposable database with seeded demo data. In an orb, run the server through `amp orb service start` rather than a background shell, using `$PORT` and `$PUBLIC_URL` for the supervised portal. The browser runner requires `puppeteer-core` and a Chrome executable **only in the opt-in test workspace**; they are not normal demo dependencies. For example, install it with `npm install --no-save --package-lock=false puppeteer-core` after installation, then run:

```sh
THEME_PREVIEW_URL=https://your-preview-host \
PUPPETEER_EXECUTABLE_PATH=/path/to/chrome \
node tools/theme-preview/browser-test.mjs
```

The hover checks require a mouse-capable browser. On Linux builds where headless Chrome reports no pointing device, run with `THEME_PREVIEW_HEADFUL=1 xvfb-run -a node tools/theme-preview/browser-test.mjs` (with the same URL and executable variables). This exercises actual desktop hover behavior under Xvfb rather than altering the application CSS for the test. The suite also checks first-click opening with the preview script blocked, keyboard opening, Escape, a separate close button, first-touch opening and reduced motion.

For an authenticated Amp portal, the optional `THEME_PREVIEW_LOGIN_URL` and `THEME_PREVIEW_SECOND_LOGIN_URL` accept two freshly minted, single-use headless login URLs. Both are consumed at the start, one per isolated browser context. Never commit these URLs. Screenshots are written to `.amp/in/artifacts/theme-preview/`; inspect them, not just the browser test's exit code. The runner verifies all sixteen appearances, single-host loading, Livewire sorting, SPA persistence, full style reloads, query/hash preservation, dirty cancellation, isolated sessions on one worker, `/app`, mobile bounds and reachable pagination, with no JavaScript errors.

## Cloud reset and deployment preflight

This installer deliberately leaves the upstream hourly database reset unchanged. **Do not enable Cloud's scheduler until its destructive reset has been tested on a dedicated disposable database.** Never connect this public-credential CRUD demo to real customer data/services. Cloud's Octane runtime uses FrankenPHP; configure persistent uploads and shared sessions/cache for multiple instances, stable `APP_KEY`, production HTTPS URL/proxy trust, secure cookies, safe mail/queue settings and worker restarts.

Before enabling the scheduler, verify actual PostgreSQL create/drop/rename-schema and `search_path` privileges; `pg_terminate_backend` privileges and impact on other/pooler connections; PgBouncer pooling behavior with persistent Octane connections through success/failure/recovery; and a shared reset lock with sessions/cache surviving schema swaps. Verify backups and recovery. Documentation alone does not establish these permissions. No reset, Cloud deployment, registry login, repository creation or push is performed by these tools.
