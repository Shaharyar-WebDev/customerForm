<?php

use App\Settings\GeneralSettings;

if (! function_exists('spa_mode')) {
    /**
     * Get the Filament instance
     */
    function spa_mode()
    {
        return app(GeneralSettings::class)->spa_mode;
    }

}

if (! function_exists('spa_link')) {
    function spa_link()
    {
        $websiteSpaMode = app(GeneralSettings::class)?->website_spa_mode ?? false;

        // dd($websiteSpaMode ? 'wire:navigate' : null);
        return $websiteSpaMode ? 'wire:navigate' : null;
    }
}
