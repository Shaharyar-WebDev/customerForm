<?php

namespace App\Filament\Pages\Navigation;

use App\Filament\Pages\Settings\ManageGeneralSettings;
use App\Filament\Pages\Settings\ManageThemeSettings;
use Filament\Navigation\NavigationItem;

class SettingsNavigation
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getDefaultSettingsRoute()
    {
        return route('filament.admin.pages.settings.general-settings');
    }

    public static function mainSettingsNavigationItem()
    {
        return NavigationItem::make('Settings')
            ->url(fn(): string => ManageGeneralSettings::getUrl())
            ->icon('heroicon-s-adjustments-horizontal')
            ->group('Settings')
            ->isActiveWhen(fn() => str_starts_with(request()->path(), 'settings'));
    }

    public static function items()
    {
        return [
            NavigationItem::make('General')
                ->url(ManageGeneralSettings::getUrl())
                ->isActiveWhen(fn(): bool => request()->routeIs(ManageGeneralSettings::getRouteName())),
            NavigationItem::make('Theme')
                ->url(ManageThemeSettings::getUrl())
                ->isActiveWhen(fn(): bool => request()->routeIs(ManageThemeSettings::getRouteName())),

        ];
    }
}
