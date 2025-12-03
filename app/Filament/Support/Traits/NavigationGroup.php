<?php

namespace App\Filament\Support\Traits;

use Illuminate\Support\Facades\Cache;

trait NavigationGroup
{
    /**
     * Create a new class instance.
     */
    protected static function getResources(): array
    {
        return [

        ];
    }

    public static function getOrder(): array
    {
        // $cacheKey = 'navigationGroup:getOrder';

        // return Cache::rememberForever($cacheKey, function () {
        return [
        ];
        // });
    }

    // protected static function getCachedResources(): array
    // {
        // $cacheKey = 'navigationGroup:resources';

        // return Cache::rememberForever($cacheKey, fn () => self::getResources());
    //     return self::getResources();
    // }

    public static function getNavigationGroup(): ?string
    {
        // self::refreshNavigationCache();
        $resources = self::getResources();

        foreach ($resources as $group => $classes) {
            if (in_array(self::class, $classes)) {
                return $group;
            }
        }

        return null;
    }

    // public static function refreshNavigationCache()
    // {
    //     Cache::forget('navigationGroup:resources');
    //     Cache::forget('navigationGroup:getOrder');
    // }
}
