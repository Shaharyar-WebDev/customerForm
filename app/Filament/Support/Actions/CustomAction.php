<?php

namespace App\Filament\Support\Actions;

use Throwable;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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

    public static function excelExporter(string $name, string $label, string $modelClass, ?string $filename = null, array|null $params = null)
    {
        return Action::make($name)
            ->label($label ?? null)
            ->icon('heroicon-o-arrow-up-tray')
            ->color('info')
            ->extraAttributes([
                'x-data' => '{}',
                'x-init' => '
            window.addEventListener(`keydown`, function(e) {
                if (e.ctrlKey && e.key === `e`) {
                    e.preventDefault();
                    $el.click();
                }
            });
        ',
            ])
            ->action(function ($record, $data) use ($modelClass, $filename, $params) {
                try {
                    if (!$filename) {
                        if ($record) {
                            $filename =
                                $record->getFileNameForPrint() ??
                                Str::slug($record->getTitleAttributeName() ?? 'export', '-');
                        } else {
                            $filename = 'Excel Export';
                        }

                    }

                    if ($record) {
                        $params = array_merge([
                            'id' => $record->id
                        ], $params ?? []);
                    }

                    $params = array_merge($data, $params ?? []);

                    return Excel::download(new $modelClass($params), $filename . '.xlsx');
                } catch (Throwable $e) {
                    Notification::make()
                        ->body("An Error Occurred While Exporting: {$e->getMessage()}")
                        ->danger()
                        ->send();
                    return;
                }
            });
    }
}
