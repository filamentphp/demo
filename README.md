# Filament Demo

A full-featured demo app built with Filament. It covers a realistic spread of admin panel patterns across three modules, an e-commerce shop, a blog, and an HR suite, so you can see how Filament handles real-world use cases.

## Getting started

Clone and install:

```sh
git clone https://github.com/filamentphp/demo.git filament-demo && cd filament-demo
composer install
composer setup
```

Start the dev server:

```sh
php artisan serve
```

Then log in at the URL shown in the terminal:

- **Email:** admin@filamentphp.com
- **Password:** password

## What's in the box

### Shop

Products, orders, customers, brands, and categories: the kind of CRUD you'd find in any e-commerce admin. The shop module demonstrates:

- **Order wizard**: multi-step create form with inline customer creation
- **Repeaters**: line items on orders and expenses with reactive totals
- **Query builder filters**: advanced filtering with text, number, date, and boolean constraints
- **Column summarizers**: footer totals for prices and quantities
- **Table grouping**: group orders by status, customer, or date
- **Drag-and-drop reordering**: sortable brand list
- **Media uploads**: multiple product images via Spatie Media Library
- **Soft deletes**: restore and force-delete on orders and customers
- **Dashboard**: filterable stats, charts, and a latest orders widget with live polling

### Blog

Posts, authors, and categories with a focus on content management patterns:

- **Rich text editing**: WYSIWYG content editor with prose rendering on the view page
- **Sub-navigation**: tabbed navigation between View, Edit, and Comments on posts
- **Manage related records**: dedicated comments page without leaving the post context
- **Tags**: Spatie Tags integration on posts
- **Import and export actions**: CSV import on categories, export on authors
- **Column layouts**: split and stack layouts on the authors table
- **Simple resources**: authors and categories managed with modals, no separate pages

### HR

Employees, departments, projects, tasks, timesheets, leave requests, and expenses: a more complex module showing how Filament scales:

- **Tabbed forms**: Personal, Employment, and Documents tabs on employees
- **Builder blocks**: milestone, task group, and checkpoint blocks in the project plan
- **Conditional fields**: salary vs. hourly rate based on employment type
- **Inline editing**: change leave request status directly in the table
- **Status workflows**: expense approval, rejection, and reimbursement actions
- **Expense line items**: repeatable entries with table layout in the infolist
- **Key-value editor**: freeform metadata on employees
- **Checkbox lists**: multi-select skills grid
- **Dashboard**: headcount stats, leave overview, timesheet trends, and budget charts

## Patterns worth looking at

Here are some specific things to poke at if you're learning Filament:

| Pattern | Where to find it |
|---|---|
| Wizard form | Create a new order |
| Reactive calculations | Edit an expense's line items |
| Builder blocks | Edit a project's Plan tab |
| Action groups with custom actions | Any table row with "..." menu |
| Slide-over modals | Ship an order |
| Modal forms with actions | Send email to a customer |
| Infolist with repeatable entries | View an expense |
| Sub-navigation | View or edit any post |
| Conditional field visibility | Change employment type on an employee |
| Dashboard filters | Shop dashboard date range and customer type |
| Global search | Press Cmd+K anywhere |
| Keyboard shortcuts | Cmd+Shift+P to quick-publish a post |
| Navigation badges | Check sidebar counts on orders, leave requests, and expenses |

## Trying the Inertia page proof of concept

Open **Inertia Vue**, **Inertia React**, or **Inertia Svelte** in the sidebar. This is an experiment, not a supported Filament API. All three frameworks passed the Chromium browser suite in light and dark modes with SSR and SPA independently enabled and disabled. These checks cover post-hydration interactions, not the pre-hydration input-loss limitation documented below. The historical observations section describes the earlier Vue prototype unless stated otherwise.

The demo links `@inertiajs/core`, `@inertiajs/vue3`, `@inertiajs/react`, and `@inertiajs/svelte` from the adjacent `../inertia` checkout (based on v3.7.0). These packages add an opt-in `externalNavigation` option to `createInertiaApp()`. The Laravel adapter is unchanged.

The host supplies `navigate(url)`, delegating to Livewire's `navigate()` with panel SPA enabled, or `location.assign()` with `INERTIA_DEMO_SPA=false`. Ordinary Inertia links and client-side URL changes delegate to the host. Same-page prop reloads and form submissions still use Inertia HTTP, but Inertia does not own browser history or scroll restoration. A successful form redirect to another page delegates to the host too, so the shell mounts with the correct context.

The Alpine wrapper mounts a framework app inside `wire:ignore` and disposes it before Livewire snapshots the document. It restores the original SSR tree for cached-page hydration. Optional host `remember()` and `restore()` callbacks use session storage scoped to the page path and query. This is not Inertia's per-history-entry storage or encrypted history. Ordinary component state survives Livewire shell updates, but resets on navigation; keyed forms use the host's remembered state.

Build the local core and adapters before `npm run build:ssr` in this demo. The SSR entry dispatches to Vue, React, or Svelte based on the page component. The routes are `/inertia-workbench`, `/react-inertia-workbench`, and `/svelte-inertia-workbench`.

The demo eagerly loads database notifications using `databaseNotifications(isLazy: false)`. With Livewire 3.8.8, a delayed notifications `__lazyLoad` response can try to morph a detached component after SPA navigation. This was reproduced on ordinary Filament pages without loading Inertia. Eager loading avoids that unrelated race in this prototype while keeping notifications enabled; it is not a fix to Livewire's handling of detached responses.

SSR visibility does not mean controls are interactive. In a delayed-renderer test, Vue and React overwrote text entered into the server-rendered notes field before hydration; Svelte retained it. This prototype does not preserve pre-hydration edits or disable those controls while loading. Functional browser tests wait for deferred props after navigation to establish that hydration has completed; those passing tests do not guarantee safe pre-hydration interaction.

### Earlier Vue-only prototype observations

`InertiaWorkbench` remains a regular `Filament\Pages\Page`. On the initial document load, Livewire mounts the page, checks page access, renders Blade and renders the panel layout. The page's Blade content embeds an actual Inertia root view, produced by `Inertia::render()->rootView()->toResponse()`. Vue owns only the element inside `wire:ignore` and `x-ignore`.

The page's `__invoke()` separates the three request lifecycles:

- Initial requests return Livewire's normal HTML response, including the Inertia root and initial props.
- `X-Inertia` visits check `canAccess()` and return Inertia JSON directly, without mounting Livewire or rendering Blade. Panel route middleware still runs.
- `/livewire/update` requests return normal Livewire responses, without constructing Inertia props or rendering their root. The existing ignored element remains in the browser.

The standard Inertia middleware handles shared errors, version conflicts and response headers. Prop construction and authorization for Inertia visits must not depend on Livewire's `mount()` or other component lifecycle hooks: those hooks intentionally do not run on this path. This prototype's props read request/session data directly.

Try editing the notes, switching to Activity, then refreshing the Livewire shell. Notes survive both operations and the counter updates without replacing the report. Back/forward navigation restores the report. Notes are local Vue state, not persisted form data; this example does not remember them across history restoration or document reloads.

Panel navigation into or out of the experiment uses full document loads. `wire:navigate` is disabled while inside it. The two report sections intentionally share one sidebar item; this does not solve synchronizing route-dependent sidebar state across different Inertia pages.

The expanded experiment uses Inertia Vue 3.7.0 and inertia-laravel 3.3.3, following the current [Inertia 3 upgrade guide](https://inertiajs.com/docs/v3/getting-started/upgrade-guide). Clearing compiled Blade views is necessary because initial page data now uses a JSON script element rather than a `data-page` attribute. It was exercised against the locally linked Filament 4.x checkout and Livewire 3.8.8, not the demo manifest's normal Filament 5 installation.

### Exercising current Inertia features

| Feature | Observed result |
|---|---|
| [Deferred props](https://inertiajs.com/docs/v3/data-props/deferred-props) | Two groups load independently after initial rendering; a nested deferred value resolves through `analytics.total`. The current demo uses a plain `analytics` array so partial requests can exclude its optional `audit` sibling. Children of a closure-returned array bypass partial filtering in inertia-laravel 3.3.3. |
| [Partial reloads](https://inertiajs.com/docs/v3/data-props/partial-reloads) | Optional and nested optional props load on demand; `always()` is included; an unrequested closure that throws is not evaluated. |
| [Once props](https://inertiajs.com/docs/v3/data-props/once-props) | A token is retained across Inertia visits; explicitly requesting the catalog generates a new token. Deferred plus `once()` also loads. |
| [Merging props](https://inertiajs.com/docs/v3/data-props/merging-props) | Contact 24 is updated, 11 retained and 37 appended; resetting removes 37 and restores the first batch. |
| [Forms and validation](https://inertiajs.com/docs/v3/the-basics/forms) | `useForm` submits via PATCH, Laravel returns a 303, errors retain input, and successful submissions update session data. `<Form>` also validates, saves and resets on success. |
| [Remembering state](https://inertiajs.com/docs/v3/data-props/remembering-state) | The keyed `useForm` draft survives back navigation. The original plain Vue notes are intentionally not remembered. |
| [Standalone HTTP](https://inertiajs.com/docs/v3/the-basics/http-requests) | `useHttp` handles 422 validation and a JSON success response without changing the saved Inertia page props. |
| [Document title](https://inertiajs.com/docs/v3/the-basics/title-and-meta) | `<Head>` updates the browser title and leaves exactly one title element alongside the Blade shell. |

The forms store only a demonstration draft in the current session. They do not modify business records. Mutation routes are inside the panel's authenticated route group, use its page middleware and explicitly check page access. Posting directly to the ordinary GET-only Livewire page returns 405; Inertia forms are not Livewire action calls.

### Enabling optional server-side rendering

SSR is optional. Following Inertia's [manual SSR setup](https://inertiajs.com/docs/v3/advanced/server-side-rendering#manual-setup), build both bundles with `npm run build:ssr`, then run `php artisan inertia:start-ssr` under your process supervisor. Node.js 22 or newer is required. The demo SSR entry binds only to loopback. In an Amp orb, start it with:

```sh
amp orb service start inertia-ssr --cwd /home/user/workspace/repos/demo --port 13714 --command 'php artisan inertia:start-ssr'
```

The existing `@inertia('filament-inertia')` directive inserts the rendered Vue HTML into Livewire's initial response. The client hydrates it using `createSSRApp()`, preserving existing DOM nodes. Without SSR, the client uses `createApp()` instead. No SSR server is required for ordinary use; `INERTIA_SSR_ENABLED=false` disables dispatch, and Inertia normally falls back to client rendering when the SSR service is unavailable. Deferred props still render their loading states first and load in subsequent Inertia requests.

The page loads its CSS in the document head, but imports the Inertia JavaScript only after `alpine:initialized` and a paint opportunity. Loading it as a normal module script delayed `DOMContentLoaded`, which delayed Livewire/Alpine revealing the shell even though SSR HTML was already present. The deferred import lets the styled SSR content appear before hydration. Without SSR, the shell appears first and the content arrives when Vue mounts. Inertia controls are not interactive until hydration completes.

This page also overrides the shell's initial opacity so content does not wait for Alpine at all. The override is scoped to `fi-inertia-page` and assumes this demo's fixed-width, non-collapsible desktop sidebar. It is not a general solution for panels that restore a collapsed sidebar from browser storage.

This is content SSR: Blade still owns the initial document head. The demo does not insert Inertia's SSR `<Head>` output into the Filament layout; its Vue `<Head>` updates the title after hydration, as before.

For strict verification, start the PHP app with `INERTIA_SSR_THROW_ON_ERROR=true`, and prefix both test commands below with `INERTIA_TEST_SSR=1`. This enables the real-server HTTP test and browser assertions that the server-rendered heading survives hydration, with no console warnings. Without that test flag, run the browser suite with SSR disabled or the SSR service stopped to verify the client-rendered path. `php artisan inertia:check-ssr` checks service health. Rebuild and restart the SSR service after changing Vue code; development/HMR SSR is not configured by this manual setup.

### Reproducing integration limitations

- **Stale Livewire context:** open Overview, then save an Inertia draft. Inertia redirects to Activity, while the shell's `mountedSection` stays `overview`, including after `refreshShell()`. This proves state divergence, not an authorization bypass: the demo has no record/tenant actions. Such actions cannot assume Inertia navigation updates their Livewire context.
- **Renderer boundary:** the probe button makes an Inertia visit to the ordinary Blade dashboard. It receives HTML, not an Inertia page. The diagnostic callback handles the HTTP exception and keeps the current page visible. Real crossings must use full navigation or `Inertia::location()`.
- **Livewire SPA coexistence:** `wire:navigate` remains disabled at the Inertia boundary. Both routers own history: Inertia expects `history.state.page`, while Livewire navigation expects `history.state.alpine.snapshotIdx`. Inertia 3.7.0 has no public router teardown or external-history mode, and repeated Vue mounting registers additional global navigation listeners. Persisting the Vue app avoids remounting but does not reconcile history. Reliable same-document coexistence needs a navigation/history integration beyond the currently supported APIs; removing the SPA exclusions alone is not sufficient.

The former discarded-rendering issues are covered by regression counters: one initial load followed by full and partial Inertia visits still produces only one Blade page/layout render; initial mount followed by two Livewire refreshes still evaluates an instrumented `Inertia::once()` prop and renders the Inertia root only once. Access-denial tests check both response types before prop construction, and invalid sections remain 404 responses.

Tests intentionally assert the remaining limitations as observed behaviour; a green suite is not a production-readiness claim. They are integration-contract issues, not demonstrated bugs in Inertia's prop implementation. Remaining untested features include uploads, Precognition, optimistic updates, polling/prefetching, deferred rescue UI, expired-session recovery, tenancy and concurrent Livewire/Inertia history writes.

Build with `npm run build`. Run the HTTP/component tests from this demo, using a disposable database:

```sh
DB_CONNECTION=sqlite DB_DATABASE=:memory: vendor/bin/pest tests/Filament/Pages/InertiaWorkbenchTest.php
```

The browser test deliberately uses the adjacent Filament checkout's existing Pest 4 Browser installation rather than upgrading this demo's Pest 3 dependencies. With this demo running locally on port 8000, run from the Filament checkout:

```sh
INERTIA_DEMO_URL=http://127.0.0.1:8000 vendor/bin/pest ../repos/demo/tests/Browser/InertiaWorkbenchTest.php
```

It checks the features and boundaries above, retained DOM identity, JavaScript errors and accessibility in light and dark modes, including validation-error states. The demo login form supplies its seeded demonstration credentials. The demo's blue refresh-button hover failed contrast checks when the expanded page scrolled it under the pointer; this POC now uses Filament's neutral button colour.
