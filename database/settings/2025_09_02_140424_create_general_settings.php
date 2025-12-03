<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        // General
        $this->migrator->add('general.site_name', 'Starter');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.account_no', null);


        $this->migrator->add('general.content_width', 'full');
        $this->migrator->add('general.navigation_type', 'sidebar');
        $this->migrator->add('general.currency_id', 1);

        // Application
        $this->migrator->add('general.spa_mode', false);
        $this->migrator->add('general.website_spa_mode', false);
        $this->migrator->add('general.spa_mode_prefetching', false);
    }
};
