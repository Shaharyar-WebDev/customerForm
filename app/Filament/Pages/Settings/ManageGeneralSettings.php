<?php

namespace App\Filament\Pages\Settings;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;
use App\Settings\GeneralSettings;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Support\Traits\Settings\HasSettingsNavigation;

class ManageGeneralSettings extends SettingsPage
{
    use HasSettingsNavigation;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = GeneralSettings::class;

    public static function getNavigationLabel(): string
    {
        return 'Settings';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()->schema([
                    Tab::make('General')->schema([
                        TextInput::make('site_name')
                            ->required(),

                        FileUpload::make('site_logo')
                            ->label('Site Logo')
                            // ->image()
                            ->directory('images/logo')
                            ->disk('public')
                            ->visibility('public')
                            ->deleteUploadedFileUsing(function ($file, GeneralSettings $generalSettings) {
                                Storage::disk('public')->delete($file);
                                $generalSettings->site_logo = null;
                                $generalSettings->save();
                            })
                            ->nullable(),

                        // Select::make('currency_id')
                        //     ->label('System currency')
                        //     ->options(Currency::pluck('name', 'id')),

                        // Select::make('default_currency_id')
                        //     ->label('Default currency')
                        //     ->options(Currency::pluck('name', 'id'))
                    ]),
                    Tab::make('Application')
                        ->schema([
                            Select::make('navigation_type')
                                ->required()
                                ->options([
                                    'sidebar' => 'Sidebar',
                                    'topbar' => 'Topbar',
                                ]),
                            Select::make('content_width')
                                ->required()
                                ->options([
                                    '7xl' => 'Large',
                                    'full' => 'Full',
                                ]),
                            Toggle::make('spa_mode')
                                ->required()
                                ->reactive(),
                            // Toggle::make('website_spa_mode')
                            //     ->required()
                            //     ->reactive(),
                            Toggle::make('spa_mode_prefetching')
                                ->required()
                                ->visible(fn($get) => $get('spa_mode') === true),

                            Action::make('reset_cache')
                                ->label('Reset cache')
                                ->action(function () {
                                    $this->redirect(route('optimize'));
                                }),

                            Action::make('clear_cache')
                                ->label('Clear cache')
                                ->action(function () {
                                    $this->redirect(route('optimize:clear'));
                                }),
                        ]),
                    Tab::make('Api')
                        ->schema([
                            TextInput::make('api_url')
                                ->nullable(),
                            TextInput::make('api_token')
                                ->nullable(),
                            Textarea::make('message_template')
                                ->nullable(),
                        ]),
                ])->columnSpanFull()
                    ->columns(2)
                    ->persistTabInQueryString()
                    ->persistTab()
                    ->id('general-settings-tabs'),
            ]);
    }

    protected function afterSave(): void
    {
        // This runs after the settings are saved
        $this->redirect(request()->header('Referer') ?? url()->current());
    }
}
