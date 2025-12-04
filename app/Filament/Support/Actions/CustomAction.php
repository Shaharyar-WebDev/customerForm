<?php

namespace App\Filament\Support\Actions;

use Throwable;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;
use Filament\Support\Exceptions\Halt;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;

class CustomAction
{
    public static function safeDelete(): DeleteAction
    {
        return DeleteAction::make()
            ->action(function ($record) {
                try {
                    DB::transaction(function () use ($record) {
                        $record->delete();
                        return;
                    });
                } catch (Throwable $e) {
                    Notification::make()
                        ->body("An Error Occurred: {$e->getMessage()}")
                        ->danger()
                        ->send();

                    throw new Halt();
                }
            });
    }

    public static function safeBulkDelete(): DeleteBulkAction
    {
        return DeleteBulkAction::make()
            ->action(function ($records) {
                try {
                    DB::transaction(function () use ($records) {
                        foreach ($records as $record) {
                            $record->delete();
                        }
                    });
                } catch (Throwable $e) {
                    Notification::make()
                        ->body("An Error Occurred: {$e->getMessage()}")
                        ->danger()
                        ->send();

                    throw new Halt();
                }
            });
    }
}
