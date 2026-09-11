<?php

use App\Filament\Pages\InertiaWorkbench;
use App\Filament\Pages\ReactInertiaWorkbench;
use App\Filament\Pages\SvelteInertiaWorkbench;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Inertia\Middleware;
use Inertia\Response as InertiaResponse;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

dataset('inertia frameworks', [
    'Vue' => [InertiaWorkbench::class, 'Workbench', 'Vue'],
    'React' => [ReactInertiaWorkbench::class, 'ReactWorkbench', 'React'],
    'Svelte' => [SvelteInertiaWorkbench::class, 'SvelteWorkbench', 'Svelte'],
]);

it('keeps database notifications enabled with lazy loading', function (): void {
    $panel = Filament::getPanel('admin');

    expect($panel->hasDatabaseNotifications())->toBeTrue()
        ->and($panel->hasLazyLoadedDatabaseNotifications())->toBeTrue();
});

it('renders an Inertia root inside the normal Livewire page response', function (string $page): void {
    $this->get($page::getUrl())
        ->assertOk()
        ->assertSee('wire:snapshot', escape: false)
        ->assertSee('id="filament-inertia"', escape: false)
        ->assertSee('fi-inertia-page')
        ->assertSee('Livewire owns this page')
        ->assertHeaderMissing('X-Inertia');
})->with('inertia frameworks');

it('keeps client rendering available when SSR is disabled', function (): void {
    config(['inertia.ssr.enabled' => false]);

    $this->get(InertiaWorkbench::getUrl())
        ->assertOk()
        ->assertSee('<div id="filament-inertia"></div>', escape: false)
        ->assertDontSee('<h2 id="inertia-report-title">', escape: false);
});

it('embeds actual server-rendered content in the first Livewire response', function (string $page): void {
    config(['inertia.ssr.enabled' => true, 'inertia.ssr.throw_on_error' => true]);

    $this->get($page::getUrl(['section' => 'activity']))
        ->assertOk()
        ->assertHeaderMissing('X-Inertia')
        ->assertSee('wire:snapshot', escape: false)
        ->assertSee('data-server-rendered="true"', escape: false)
        ->assertSee('id="inertia-report-title"', escape: false)
        ->assertSee('Recent activity')
        ->assertSee('Loading analytics…')
        ->assertSee('id="draft-title"', escape: false);
})->with('inertia frameworks')->skip(! getenv('INERTIA_TEST_SSR'), 'Requires the built SSR bundle and running SSR server.');

it('returns Inertia JSON directly for an `X-Inertia` visit', function (string $page, string $component): void {
    $this->get($page::getUrl(['section' => 'activity']), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
    ])
        ->assertOk()
        ->assertHeader('X-Inertia', 'true')
        ->assertJsonPath('component', $component)
        ->assertJsonPath('props.section', 'activity')
        ->assertJsonPath('props.report.title', 'Recent activity')
        ->assertJsonPath('props.endpoints.overview', $page::getUrl(['section' => 'overview']))
        ->assertJsonPath('props.endpoints.activity', $page::getUrl(['section' => 'activity']))
        ->assertDontSee('wire:snapshot', escape: false);
})->with('inertia frameworks');

it('honors Inertia partial reloads without rendering missing props into Blade', function (string $page, string $component): void {
    $this->get($page::getUrl(['section' => 'activity']), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
        'X-Inertia-Partial-Component' => $component,
        'X-Inertia-Partial-Data' => 'report',
    ])
        ->assertOk()
        ->assertJsonPath('props.report.title', 'Recent activity')
        ->assertJsonMissingPath('props.section');
})->with('inertia frameworks');

it('retains Inertia asset version conflict handling', function (): void {
    $this->get(InertiaWorkbench::getUrl(), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => 'outdated-build',
    ])
        ->assertStatus(409)
        ->assertHeader('X-Inertia-Location', InertiaWorkbench::getUrl());
});

it('requires authentication before rendering Inertia props', function (): void {
    auth()->logout();

    $this->get(InertiaWorkbench::getUrl(), ['X-Inertia' => 'true'])
        ->assertRedirect();
});

it('checks `canAccess()` before building props for both response types', function (bool $isInertiaRequest): void {
    Route::get('/denied-inertia-workbench', DeniedInertiaWorkbench::class)->middleware('web');

    $this->get('/denied-inertia-workbench', $isInertiaRequest ? ['X-Inertia' => 'true'] : [])
        ->assertForbidden();
})->with([false, true]);

it('rejects an unknown section for both response types', function (bool $isInertiaRequest): void {
    $this->get(InertiaWorkbench::getUrl(['section' => 'unknown']), $isInertiaRequest ? [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
    ] : [])
        ->assertNotFound();
})->with([false, true]);

it('can still update the Livewire shell using `refreshShell()`', function (): void {
    Livewire::test(InertiaWorkbench::class)
        ->call('refreshShell')
        ->assertSet('refreshCount', 1);
});

it('exposes nested deferred metadata but omits optional props on the initial visit', function (string $page): void {
    $this->get($page::getUrl(), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
    ])->assertOk()
        ->assertJsonPath('deferredProps.analytics', ['analytics.total'])
        ->assertJsonPath('deferredProps.permissions', ['permissions'])
        ->assertJsonMissingPath('props.analytics.total')
        ->assertJsonMissingPath('props.analytics.audit')
        ->assertJsonMissingPath('props.optionalSummary');
})->with('inertia frameworks');

it('omits `analytics.audit` when only `analytics.total` is requested', function (string $page, string $component): void {
    $this->get($page::getUrl(), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
        'X-Inertia-Partial-Component' => $component,
        'X-Inertia-Partial-Data' => 'analytics.total',
    ])->assertOk()
        ->assertJsonPath('props.analytics.total', 137)
        ->assertJsonMissingPath('props.analytics.audit');
})->with('inertia frameworks');

it('resolves selected nested props and includes `always()` props without evaluating other closures', function (): void {
    Inertia::share('unrequested', static fn () => throw new RuntimeException('Unrequested closure evaluated'));

    $this->get(InertiaWorkbench::getUrl(), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
        'X-Inertia-Partial-Component' => 'Workbench',
        'X-Inertia-Partial-Data' => 'analytics.total,analytics.audit,optionalSummary',
    ])->assertOk()
        ->assertJsonPath('props.analytics.total', 137)
        ->assertJsonPath('props.analytics.audit', 'Nested optional audit loaded')
        ->assertJsonPath('props.optionalSummary', 'Optional: 24 active accounts')
        ->assertJsonStructure(['props' => ['requestToken']])
        ->assertJsonMissingPath('props.unrequested')
        ->assertJsonMissingPath('props.report');
});

it('renders Blade only on the initial load, not on Inertia visits or partial reloads', function (): void {
    $layoutRenders = 0;
    $pageRenders = 0;
    View::composer('filament-panels::components.layout.index', function () use (&$layoutRenders): void {
        $layoutRenders++;
    });
    View::composer('filament.pages.inertia-workbench', function () use (&$pageRenders): void {
        $pageRenders++;
    });

    $this->get(InertiaWorkbench::getUrl())->assertOk();
    expect($layoutRenders)->toBe(1)->and($pageRenders)->toBe(1);

    $this->get(InertiaWorkbench::getUrl(), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
    ])->assertOk()->assertHeader('X-Inertia', 'true');

    $this->get(InertiaWorkbench::getUrl(), [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(Middleware::class)->version(request()),
        'X-Inertia-Partial-Component' => 'Workbench',
        'X-Inertia-Partial-Data' => 'optionalSummary',
    ])->assertOk();

    expect($layoutRenders)->toBe(1)->and($pageRenders)->toBe(1);
});

it('does not evaluate Inertia props or render their root on Livewire updates', function (): void {
    $evaluations = 0;
    $rootRenders = 0;
    View::composer('filament.pages.inertia-root', function () use (&$rootRenders): void {
        $rootRenders++;
    });
    Inertia::share('expensive', Inertia::once(function () use (&$evaluations): int {
        return ++$evaluations;
    }));

    $component = Livewire::test(InertiaWorkbench::class);
    expect($evaluations)->toBe(1)->and($rootRenders)->toBe(1);

    $component->call('refreshShell')->assertSet('refreshCount', 1);
    $component->call('refreshShell')->assertSet('refreshCount', 2);
    expect($evaluations)->toBe(1)->and($rootRenders)->toBe(1);
});

it('needs a separate mutation endpoint rather than posting to the Livewire page', function (): void {
    $this->post(InertiaWorkbench::getUrl(), ['title' => 'A new draft'], ['X-Inertia' => 'true'])
        ->assertStatus(405);
});

it('redirects a successful Inertia `PATCH` with `303` and validates failed submissions', function (string $page, string $component, string $framework): void {
    $this->from($page::getUrl())
        ->patch(route($page::getRouteName() . '.draft'), ['title' => 'No'], ['X-Inertia' => 'true'])
        ->assertRedirect($page::getUrl())
        ->assertSessionHasErrors('title');

    $this->patch(route($page::getRouteName() . '.draft'), ['title' => 'Revised quarterly draft'], ['X-Inertia' => 'true'])
        ->assertStatus(303)
        ->assertRedirect($page::getUrl(['section' => 'activity']))
        ->assertSessionHas("inertia-workbench.{$framework}.draft.title", 'Revised quarterly draft');
})->with('inertia frameworks');

it('returns JSON validation errors to `useHttp()` rather than an Inertia redirect', function (string $page): void {
    $this->postJson(route($page::getRouteName() . '.http'), ['title' => 'No'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('title');

    $this->postJson(route($page::getRouteName() . '.http'), ['title' => 'Standalone draft'])
        ->assertOk()
        ->assertExactJson(['title' => 'Standalone draft']);
})->with('inertia frameworks');

class DeniedInertiaWorkbench extends InertiaWorkbench
{
    public static function canAccess(): bool
    {
        return false;
    }

    protected function getInertiaResponse(): InertiaResponse
    {
        throw new RuntimeException('Unauthorized props evaluated');
    }
}
