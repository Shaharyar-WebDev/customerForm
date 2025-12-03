<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Illuminate\View\View;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use App\Settings\ThemeSettings;
use App\Filament\Pages\Dashboard;
use App\Settings\GeneralSettings;
use Filament\View\PanelsIconAlias;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Support\Traits\NavigationGroup;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use App\Filament\Pages\Navigation\SettingsNavigation;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    protected ThemeSettings $themeSettings;

    protected GeneralSettings $generalSettings;

    protected function resolveSettings(): void
    {
        $this->themeSettings = app(ThemeSettings::class);
        $this->generalSettings = app(GeneralSettings::class);
        // dd($this->generalSettings->site_logo);
    }

    public function panel(Panel $panel): Panel
    {
        $this->resolveSettings();

        return $panel
            ->default()
            ->id('admin')
            ->path('')
            // ->login(null)
            ->profile()
            ->globalSearch(false)
            ->defaultThemeMode(ThemeMode::Light)
            ->sidebarCollapsibleOnDesktop(true)
            ->sidebarFullyCollapsibleOnDesktop(false)
            ->unsavedChangesAlerts(false)
            ->databaseTransactions(true)
            ->strictAuthorization(false)
            ->brandName($this->generalSettings->site_name)
            ->brandLogo(fn() => $this->generalSettings->site_logo ? asset('storage/' . $this->generalSettings->site_logo) : null)
            ->spa(fn() => $this->generalSettings->spa_mode, fn() => $this->generalSettings->spa_mode_prefetching)
            ->maxContentWidth($this->generalSettings->content_width ?? '7xl')
            ->topNavigation(fn() => $this->generalSettings->navigation_type === 'topbar')
            ->colors(fn() => $this->themeSettings->use_custom_theme === true ? $this->themeSettings->getColors() : [])
            ->renderHook(PanelsRenderHook::CONTENT_BEFORE, fn(): View => view('partials.gradient-bg', [
                'backgroudType' => $this->themeSettings->background_type ?? 'gradient',
            ]))
            ->renderHook(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, fn(): View => view('partials.auth-gradient-bg', [
                'backgroudType' => $this->themeSettings->background_type ?? 'gradient',
            ]))
            ->renderHook(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, fn(): View => view('partials.copyright-label'))
            ->renderHook(PanelsRenderHook::SCRIPTS_AFTER, fn(): View => view('partials.scripts'))
            ->font('Poppins')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationItems([
                SettingsNavigation::mainSettingsNavigationItem(),
            ])
            ->navigationGroups(NavigationGroup::getOrder())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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
                // Authenticate::class,
            ]);
    }

    public function boot()
    {
        // sleep(1);
        $this->resolveSettings();

        FilamentIcon::register([
            PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => view('partials.sidebar-toggle'),
            PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => view('partials.sidebar-toggle'),
        ]);

        FilamentAsset::register([
            // Js::make('custom-scripts'),
        ]);

        FilamentAsset::registerCssVariables([
            'background-image' => 'url(' . $this->themeSettings->getBackgroundPatternImage() . ')',
        ]);
    }
}
