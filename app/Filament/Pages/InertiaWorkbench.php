<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Pages\PageConfiguration;
use Filament\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Middleware;
use Inertia\Response as InertiaResponse;
use Livewire\Attributes\Locked;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

class InertiaWorkbench extends Page
{
    protected static ?string $title = 'Inertia Vue';

    protected static string $inertiaComponent = 'Workbench';

    protected static string $rendererModule = 'resources/js/inertia-workbench.js';

    protected static string $framework = 'Vue';

    protected static ?int $navigationSort = 4;

    protected static string | array $routeMiddleware = [Middleware::class];

    protected string $view = 'filament.pages.inertia-workbench';

    protected array $extraBodyAttributes = ['class' => 'fi-inertia-page'];

    public int $refreshCount = 0;

    #[Locked]
    public string $mountedSection = 'overview';

    public function mount(): void
    {
        $this->mountedSection = request()->query('section', 'overview');
    }

    public static function routes(Panel $panel, ?PageConfiguration $configuration = null): void
    {
        parent::routes($panel, $configuration);

        Route::match(['post', 'patch'], static::getRoutePath($panel) . '/draft', static function (Request $request) {
            abort_unless(static::canAccess(), 403);

            $draft = $request->validate([
                'title' => ['required', 'string', 'min:5', 'max:120'],
            ]);

            $request->session()->put(static::getDraftSessionKey(), $draft);

            return redirect()->to(static::getUrl(['section' => 'activity']));
        })->middleware(static::getRouteMiddleware($panel))
            ->name(static::getRelativeRouteName($panel) . '.draft');

        Route::post(static::getRoutePath($panel) . '/http', static function (Request $request) {
            abort_unless(static::canAccess(), 403);

            return response()->json($request->validate([
                'title' => ['required', 'string', 'min:5', 'max:120'],
            ]));
        })->middleware(static::getRouteMiddleware($panel))
            ->name(static::getRelativeRouteName($panel) . '.http');
    }

    public function __invoke(): Response
    {
        if (request()->header('X-Inertia')) {
            abort_unless(static::canAccess(), 403);

            return $this->getInertiaResponse()->toResponse(request());
        }

        return parent::__invoke();
    }

    public function refreshShell(): void
    {
        $this->refreshCount++;
    }

    protected function getViewData(): array
    {
        return [
            'rendererModule' => static::$rendererModule,
            'framework' => static::$framework,
            'inertiaView' => new HtmlString(Livewire::isLivewireRequest()
                ? ''
                : $this->getInertiaResponse()->toResponse(request())->getContent()),
        ];
    }

    protected function getInertiaResponse(): InertiaResponse
    {
        $section = request()->query('section', 'overview');

        abort_unless(in_array($section, ['overview', 'activity'], true), 404);

        return Inertia::render(static::$inertiaComponent, [
            'section' => $section,
            'report' => $section === 'overview'
                ? ['title' => 'Quarterly overview', 'description' => 'Revenue grew by 18% across 24 customer accounts.']
                : ['title' => 'Recent activity', 'description' => 'Seven proposals were approved this week.'],
            'requestToken' => Inertia::always(static fn (): string => (string) Str::uuid()),
            'optionalSummary' => Inertia::optional(static fn (): string => 'Optional: 24 active accounts'),
            'catalog' => Inertia::once(static fn (): array => ['token' => (string) Str::uuid(), 'plans' => ['Standard', 'Enterprise']]),
            'analytics' => [
                'total' => Inertia::defer(static fn (): int => 137, 'analytics'),
                'audit' => Inertia::optional(static fn (): string => 'Nested optional audit loaded'),
            ],
            'permissions' => Inertia::defer(static fn (): array => ['review', 'publish'], 'permissions')->once(),
            'contacts' => Inertia::merge(request()->integer('batch', 1) === 1
                ? ['data' => [['id' => 11, 'name' => 'Aster'], ['id' => 24, 'name' => 'Birch']], 'batch' => 1]
                : ['data' => [['id' => 24, 'name' => 'Birch revised'], ['id' => 37, 'name' => 'Cedar']], 'batch' => 2])
                ->append('data', matchOn: 'id'),
            'savedDraft' => static fn (): array => session(static::getDraftSessionKey(), ['title' => 'Quarterly review']),
            'endpoints' => [
                'overview' => static::getUrl(['section' => 'overview']),
                'activity' => static::getUrl(['section' => 'activity']),
                'draft' => route(static::getRouteName() . '.draft', absolute: false),
                'http' => route(static::getRouteName() . '.http', absolute: false),
            ],
        ])->rootView('filament.pages.inertia-root');
    }

    protected static function getDraftSessionKey(): string
    {
        return 'inertia-workbench.' . static::$framework . '.draft';
    }
}
