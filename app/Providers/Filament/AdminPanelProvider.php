<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\DataSources\Shop\BrandWidgetDataSource;
use App\Filament\Widgets\DataSources\Shop\CategoryWidgetDataSource;
use App\Filament\Widgets\DataSources\Shop\CustomerWidgetDataSource;
use App\Filament\Widgets\DataSources\Shop\OrderWidgetDataSource;
use App\Filament\Widgets\DataSources\Shop\PaymentWidgetDataSource;
use App\Filament\Widgets\DataSources\Shop\ProductWidgetDataSource;
use App\Filament\Widgets\SalesByCategoryChart;
use App\Filament\Widgets\CustomersByValueAndFrequencyChart;
use App\Filament\Widgets\CustomersChart;
use App\Filament\Widgets\RevenueShareByChannelChart;
use App\Filament\Widgets\LatestOrders;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\OrderDistributionByStatusChart;
use App\Filament\Widgets\TrafficSourcesChart;
use App\Filament\Widgets\QualityMetricsComparisonChart;
use App\Filament\Widgets\PriceQuantityChart;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Http\Middleware\Authenticate;
use Filament\CustomDashboardsPlugin\CustomDashboardsPlugin;
use Filament\Enums\DatabaseNotificationsPosition;
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->login(Login::class)
            ->plugins([
                CustomDashboardsPlugin::make()
                    ->widgetDataSources([
                        BrandWidgetDataSource::class,
                        CategoryWidgetDataSource::class,
                        CustomerWidgetDataSource::class,
                        OrderWidgetDataSource::class,
                        PaymentWidgetDataSource::class,
                        ProductWidgetDataSource::class,
                    ])
                    ->widgets([
                        StatsOverviewWidget::class,
                        CustomersChart::class,
                        RevenueShareByChannelChart::class,
                        LatestOrders::class,
                        SalesByCategoryChart::class,
                        OrdersChart::class,
                        PriceQuantityChart::class,
                        OrderDistributionByStatusChart::class,
                    ]),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->unsavedChangesAlerts()
            ->brandLogo(fn () => view('filament.app.logo'))
            ->brandLogoHeight('1.25rem')
            ->navigationGroups([
                'Shop',
                'Blog',
            ])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->spa()
            ->colors([
                'primary' => Color::Blue,
            ]);
    }
}
