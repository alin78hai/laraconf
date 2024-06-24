<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
// use Filament\Support\Facades\FilamentView;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
// use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('/')
            ->login()
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Stone,
            ])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                StatsOverviewWidget::class,
            ])
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
            ->navigationItems([
                NavigationItem::make('Analytics')
                    ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-presentation-chart-line')
                    ->group('Reports')
                    ->sort(3),
                // NavigationItem::make('dashboard')
                //     ->label(fn (): string => __('filament-panels::pages/dashboard.title'))
                //     ->url(fn (): string => Dashboard::getUrl())
                //     ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.dashboard')),
                // ...
            ]);
    }

    // public function register(): void
    // {
    //     parent::register();

    //     FilamentView::registerRenderHook('panels::body.end', fn(): string => Blade::render("@vite('resources/js/app.js)"));
    // }

    public function boot(): void
    {
        CreateAction::configureUsing(fn($action) => $action->slideOver());
        EditAction::configureUsing(fn($action) => $action->slideOver());
    }
}
