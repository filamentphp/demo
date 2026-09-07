<?php

use App\Filament\DemoIconAlias;
use App\LiveDemo\DemoMaterialSymbols;
use App\LiveDemo\PreviewPanel;
use App\LiveDemo\Selection;
use Filament\FontProviders\BunnyFontProvider;
use Filament\FontProviders\LocalFontProvider;
use Filament\NoirTheme\NoirThemeColor;
use Filament\SharpTheme\SharpThemeMaterialSymbols;
use Filament\SoftTheme\SoftThemeColor;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

uses(TestCase::class);

it('describes the theme choices and desktop-only density change', function (): void {
    $this->withoutVite();

    $this->view('live-demo.toolbar')
        ->assertSee('Switch theme')
        ->assertSee('Filament themes')
        ->assertSee('Choose a new look, a denser layout, or both.')
        ->assertSee('Default')
        ->assertSee('Precise & technical')
        ->assertSee('Warm & welcoming')
        ->assertSee('Dark-first depth')
        ->assertSee('Add Compact')
        ->assertSee('More room on desktop. Same spacing on mobile.')
        ->assertSee('$39 USD')
        ->assertSee('$29 USD')
        ->assertSee('Free')
        ->assertDontSee('Single-project licenses')
        ->assertSee('https://filamentphp.com/themes')
        ->assertDontSee('live-demo-studio__status');
});

beforeEach(function (): void {
    Route::middleware([StartSession::class, SubstituteBindings::class])
        ->match(['GET', 'POST'], '/_live-demo-test', fn () => response()->json([
            'selection' => Selection::current(),
            'vite' => app(PreviewPanel::class)->getViteTheme(),
        ]));
});

it('boots the actual panel with session-selected colors and icons', function (string $theme, string $primary, ?string $icon): void {
    $panel = app('filament')->getPanel('admin');
    $alias = array_key_first(SharpThemeMaterialSymbols::Aliases);
    Route::middleware($panel->getMiddleware())
        ->get('/_live-demo-panel-test', fn () => response()->json([
            'primary' => FilamentColor::getColors()['primary'][500],
            'icon' => FilamentIcon::resolve($alias),
        ]));

    $this->withSession(['live-demo.selection' => ['theme' => $theme]])
        ->getJson('/_live-demo-panel-test')
        ->assertOk()
        ->assertExactJson(['primary' => $primary, 'icon' => $icon]);
})->with([
    'stock' => ['stock', Color::Blue[500], null],
    'sharp' => ['sharp', Color::Blue[500], array_values(SharpThemeMaterialSymbols::Aliases)[0]],
    'soft' => ['soft', SoftThemeColor::Primary[500], null],
    'noir' => ['noir', NoirThemeColor::Primary[500], null],
]);

function withBoundRequest(array $selection, callable $callback): void
{
    $previous = app('request');
    $request = Request::create('/preview', 'GET');
    $session = new Store('live-demo-test', new ArraySessionHandler(120));
    $session->start();
    $session->put('live-demo.selection', array_replace([
        'theme' => 'stock',
        'compact' => false,
        'expanded' => false,
    ], $selection));
    $request->setLaravelSession($session);
    app()->instance('request', $request);

    try {
        $callback($request);
    } finally {
        app()->instance('request', $previous);
    }
}

it('defaults GET requests to the stock non-compact host', function (): void {
    $this->getJson('/_live-demo-test')
        ->assertOk()
        ->assertExactJson([
            'selection' => ['theme' => 'stock', 'compact' => false, 'expanded' => false],
            'vite' => 'resources/css/live-demo/stock.css',
        ]);
});

it('selects each of the eight choice hosts', function (string $theme, string $compact, string $host): void {
    $this->getJson("/_live-demo-test?theme={$theme}&compact={$compact}")
        ->assertOk()
        ->assertJsonPath('selection', [
            'theme' => $theme,
            'compact' => $compact === '1',
            'expanded' => true,
        ])
        ->assertJsonPath('vite', "resources/css/live-demo/{$host}.css");
})->with([
    'stock' => ['stock', '0', 'stock'],
    'stock compact' => ['stock', '1', 'stock-compact'],
    'sharp' => ['sharp', '0', 'sharp'],
    'sharp compact' => ['sharp', '1', 'sharp-compact'],
    'soft' => ['soft', '0', 'soft'],
    'soft compact' => ['soft', '1', 'soft-compact'],
    'noir' => ['noir', '0', 'noir'],
    'noir compact' => ['noir', '1', 'noir-compact'],
]);

it('matches palette, fonts, and sharp aliases to the theme packages', function (): void {
    $previewPanel = app(PreviewPanel::class);

    withBoundRequest(['theme' => 'soft'], function () use ($previewPanel): void {
        $this->assertSame(SoftThemeColor::Colors, $previewPanel->getColors());
        $this->assertSame('Albert Sans', $previewPanel->getFontFamily());
        $this->assertSame(BunnyFontProvider::class, $previewPanel->getFontProvider());
        $this->assertSame('Lora', $previewPanel->getSerifFontFamily());
        $this->assertTrue($previewPanel->hasCustomSerifFontFamily());
    });

    withBoundRequest(['theme' => 'noir'], function () use ($previewPanel): void {
        $this->assertSame(NoirThemeColor::Colors, $previewPanel->getColors());
        $this->assertSame('Inter Variable', $previewPanel->getFontFamily());
        $this->assertSame(LocalFontProvider::class, $previewPanel->getFontProvider());
        $this->assertSame([], $previewPanel->getIcons());
    });

    withBoundRequest(['theme' => 'sharp'], function () use ($previewPanel): void {
        $this->assertSame([...SharpThemeMaterialSymbols::Aliases, ...DemoMaterialSymbols::Aliases], $previewPanel->getIcons());
        $this->assertSame(['primary' => Color::Blue], $previewPanel->getColors());
        $this->assertSame('Inter Variable', $previewPanel->getFontFamily());
        $this->assertSame(LocalFontProvider::class, $previewPanel->getFontProvider());
    });
});

it('ignores invalid query arrays and values', function (): void {
    $this->withSession(['live-demo.selection' => [
        'theme' => 'soft',
        'compact' => true,
        'expanded' => true,
    ]])->getJson('/_live-demo-test?theme[]=sharp&compact[]=0')
        ->assertJsonPath('selection', ['theme' => 'soft', 'compact' => true, 'expanded' => true]);

    $this->getJson('/_live-demo-test?theme=unknown&compact=yes')
        ->assertJsonPath('selection', ['theme' => 'soft', 'compact' => true, 'expanded' => true]);

    $this->withSession(['live-demo.selection' => ['theme' => ['sharp'], 'compact' => true]])
        ->getJson('/_live-demo-test')
        ->assertJsonPath('selection', ['theme' => 'stock', 'compact' => false, 'expanded' => false]);
});

it('persists selection and prevents POST requests from replacing it', function (): void {
    $this->getJson('/_live-demo-test?theme=noir&compact=1')
        ->assertJsonPath('vite', 'resources/css/live-demo/noir-compact.css');

    $this->postJson('/_live-demo-test?theme=sharp&compact=0', [
        '_token' => 'livewire-like-request',
        'components' => [['snapshot' => '{}']],
    ])->assertJsonPath('selection', ['theme' => 'noir', 'compact' => true, 'expanded' => true]);

    $this->getJson('/_live-demo-test')
        ->assertJsonPath('vite', 'resources/css/live-demo/noir-compact.css');
});

it('does not leak one preview panel identity between sequential sessions', function (): void {
    $previewPanel = app(PreviewPanel::class);

    withBoundRequest(['theme' => 'sharp', 'compact' => true], function () use ($previewPanel): void {
        $this->assertSame('resources/css/live-demo/sharp-compact.css', $previewPanel->getViteTheme());
        $this->assertSame([...SharpThemeMaterialSymbols::Aliases, ...DemoMaterialSymbols::Aliases], $previewPanel->getIcons());
    });

    withBoundRequest(['theme' => 'soft'], function () use ($previewPanel): void {
        $this->assertSame('resources/css/live-demo/soft.css', $previewPanel->getViteTheme());
        $this->assertSame([], $previewPanel->getIcons());
        $this->assertSame(SoftThemeColor::Colors, $previewPanel->getColors());
    });
});

it('memoizes selection on the current request', function (): void {
    $previewPanel = app(PreviewPanel::class);

    withBoundRequest(['theme' => 'sharp'], function (Request $request) use ($previewPanel): void {
        $first = Selection::current();
        $request->session()->put('live-demo.selection', ['theme' => 'noir']);

        $this->assertSame($first, Selection::current());
        $this->assertSame($first, $request->attributes->get('live-demo.selection'));
        $this->assertSame('resources/css/live-demo/sharp.css', $previewPanel->getViteTheme());
    });
});

it('keeps the second application panel stock', function (): void {
    $panel = app('filament')->getPanel('app');

    $this->assertNotInstanceOf(PreviewPanel::class, $panel);
    $this->assertNull($panel->getViteTheme());
    $this->assertSame([], $panel->getIcons());
});

it('provides renderable Sharp icons while preserving the original brand artwork', function (): void {
    $aliases = array_values((new ReflectionClass(DemoIconAlias::class))->getConstants());
    $brands = [
        DemoIconAlias::RESOURCES_BLOG_AUTHORS_FIELDS_GITHUB => 'icon-github',
        DemoIconAlias::RESOURCES_BLOG_AUTHORS_ACTIONS_VIEW_GITHUB => 'icon-github',
        DemoIconAlias::RESOURCES_BLOG_AUTHORS_FIELDS_TWITTER => 'icon-twitter',
        DemoIconAlias::RESOURCES_BLOG_AUTHORS_ACTIONS_VIEW_TWITTER => 'icon-twitter',
    ];

    expect(array_keys(DemoMaterialSymbols::Aliases))->toEqualCanonicalizing($aliases);

    foreach (DemoMaterialSymbols::Aliases as $alias => $icon) {
        if (isset($brands[$alias])) {
            expect($icon)->toBe($brands[$alias]);
        } else {
            expect($icon)->toStartWith('gmsi-s-');
        }

        expect(svg($icon)->toHtml())->toContain('<svg');
    }
});

it('preserves the original symbols rather than reinterpreting their action names', function (): void {
    expect(DemoMaterialSymbols::Aliases)
        ->toMatchArray([
            DemoIconAlias::RESOURCES_HR_EMPLOYEES_BULK_ACTIONS_TOGGLE_ACTIVE => 'gmsi-s-power_settings_new',
            DemoIconAlias::WIDGETS_WORKFORCE_TURNOVER => 'gmsi-s-logout',
            DemoIconAlias::WIDGETS_WORKFORCE_CAPACITY => 'gmsi-s-person_add',
            DemoIconAlias::WIDGETS_WORKFORCE_CONTRACTORS => 'gmsi-s-work',
            DemoIconAlias::WIDGETS_WORKFORCE_TENURE => 'gmsi-s-schedule',
            DemoIconAlias::WIDGETS_FEATURES_HEADING => 'gmsi-s-stars_2',
            DemoIconAlias::ENUMS_ORDER_STATUS_NEW => 'gmsi-s-stars_2',
            DemoIconAlias::WIDGETS_FEATURES_LINK => 'gmsi-s-chevron_right',
            DemoIconAlias::THEME_PREVIEW_PRICING => 'gmsi-s-north_east',
        ]);
});

it('registers demo icons only when the actual panel boots Sharp', function (string $theme): void {
    $panel = app('filament')->getPanel('admin');
    Route::middleware($panel->getMiddleware())
        ->get('/_live-demo-icons-test', fn () => response()->json([
            'icon' => FilamentIcon::resolve(DemoIconAlias::RESOURCES_SHOP_PRODUCTS_NAVIGATION),
        ]));

    $this->withSession(['live-demo.selection' => ['theme' => $theme]])
        ->getJson('/_live-demo-icons-test')
        ->assertOk()
        ->assertExactJson(['icon' => $theme === 'sharp' ? 'gmsi-s-bolt' : null]);
})->with(['stock', 'sharp', 'soft', 'noir']);
