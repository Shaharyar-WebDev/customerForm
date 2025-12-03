<?php

namespace App\Filament\Support\Traits;

use Illuminate\Database\Eloquent\Model;

trait ReadOnlyResource
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function canCreate(): bool
    {
        return false; // disables the "Create" button
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
