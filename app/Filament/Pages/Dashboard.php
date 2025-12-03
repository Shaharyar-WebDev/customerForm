<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static string $routePath = 'dashboard';

    protected static ?string $title = null;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    public function getColumns(): int|array
    {
        return 2;
    }

    protected int|string|array $columnSpan = 'full';
}
