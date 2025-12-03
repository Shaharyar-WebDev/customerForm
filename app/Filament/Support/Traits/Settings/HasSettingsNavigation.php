<?php

namespace App\Filament\Support\Traits\Settings;

use UnitEnum;
use Filament\Panel;
use Illuminate\Support\Str;
use App\Filament\Pages\Navigation\SettingsNavigation;
use App\Filament\Pages\Settings\ManageGeneralSettings;

trait HasSettingsNavigation
{

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Settings';
        // return null;
    }

    public function getSubNavigation(): array
    {
        return SettingsNavigation::items();
    }

    public function getBreadcrumbs(): array
    {
        $baseName = class_basename(static::class);
        $pageTitle = Str::headline(Str::replaceFirst('Manage', '', $baseName));
        return [
            route('default-settings') => 'Settings',
            url()->current() => $pageTitle,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Only register the navigation item in main sidebar that is inside this array
        $classes = [
                // ManageThemeSettings::class,
            ManageGeneralSettings::class
        ];
        // return in_array(static::class, $classes);
        return false;
    }

    public static function getSlug(?Panel $panel = null): string
    {
        $baseName = class_basename(static::class);

        $slug = Str::kebab(Str::replaceFirst('Manage', '', $baseName));

        // if ($baseName === 'ManageGeneralSettings') {
        //     return $slug;
        // }

        return "settings/$slug";
    }

}
