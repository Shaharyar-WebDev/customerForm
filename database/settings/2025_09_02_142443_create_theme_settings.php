<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        //  Colors
        $this->migrator->add('theme.danger_color', 'Rose');
        $this->migrator->add('theme.gray_color', 'Slate');
        $this->migrator->add('theme.info_color', 'Blue');
        $this->migrator->add('theme.primary_color', 'Orange');
        $this->migrator->add('theme.success_color', 'Emerald');
        $this->migrator->add('theme.warning_color', 'Amber');

        $this->migrator->add('theme.use_custom_theme', false);
        // Background
        $this->migrator->add('theme.background_type', 'pattern');

    }
};
