<?php

namespace App\Filament\Pages\Settings;

use BackedEnum;
use Filament\Schemas\Schema;
use App\Settings\ThemeSettings;
use Filament\Pages\SettingsPage;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Illuminate\Support\Facades\Cache;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Support\Traits\Settings\HasSettingsNavigation;

class ManageThemeSettings extends SettingsPage
{
    use HasSettingsNavigation;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = ThemeSettings::class;

    protected function getColorOptions()
    {
        $options = [];
        foreach (ThemeSettings::colorsMap() as $colorName => $class) {
            $options[$colorName] = '<div class="flex items-center gap-2"><div class="w-4 h-4 rounded border border-gray-300" style="background-color: ' . $class[500] . '"></div>' . $colorName . '</div>';
        }
        return $options;
    }

    protected function createColorSelect(string $field, string $label): Select
    {
        return Select::make($field)
            ->label($label)
            ->native(false)
            ->allowHtml()
            ->required()
            ->searchable()
            ->options(
                // fn() =>
                // Cache::rememberForever('color:options', function () {
                // return 
                $this->getColorOptions()
                // ;
                // })
            )
            ->rules('in:' . ThemeSettings::getColorKeysRule());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section::make()->schema([
                Tabs::make()->tabs([
                    Tab::make('Colors')->schema([
                        $this->createColorSelect('primary_color', 'Primary Color'),
                        $this->createColorSelect('danger_color', 'Danger Color'),
                        $this->createColorSelect('gray_color', 'Gray Color'),
                        $this->createColorSelect('info_color', 'Info Color'),
                        $this->createColorSelect('success_color', 'Success Color'),
                        $this->createColorSelect('warning_color', 'Warning Color'),
                        Toggle::make('use_custom_theme')
                            ->required(),
                    ]),
                    Tab::make('Background')->schema([
                        Select::make('background_type')
                            ->options([
                                'plain' => 'Plain',
                                'gradient' => 'Gradient',
                                'pattern' => 'Pattern'
                            ])
                            ->required()
                            ->rules('in:gradient,pattern,plain')
                    ]),
                ])->columnSpanFull()
                    ->columns(2)
                    ->persistTabInQueryString()
                    ->persistTab()
                    ->id('theme-settings-tabs'),
                // ])
            ]);
    }

    protected function afterSave(): void
    {
        // This runs after the settings are saved
        // Cache::forget(ThemeSettings::cacheKeys()['panelColors']);
        $this->redirect(request()->header('Referer') ?? url()->current());
    }
}
