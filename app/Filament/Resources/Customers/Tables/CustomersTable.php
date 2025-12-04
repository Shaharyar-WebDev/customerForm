<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Jobs\SendCustomerMessageJob;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use App\Filament\Support\Actions\CustomAction;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable()
                    ->copyable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('phone_number')
                    ->label('WhatsApp No.')
                    ->prefix('+92')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('visit_frequency')
                    ->label('Visit Frequency')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('category')
                    ->label('Customer Category')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('date')
                    ->label('Date')
                    ->date('d-M-Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->toggleable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'sent',
                        'danger' => 'failed',
                    ]),

                TextColumn::make('sent_at')
                    ->label('Sent At')
                    ->date('d-M-Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('error')
                    ->label('Remarks')
                    ->toggleable()
                    ->copyable()
                    ->formatStateUsing(function ($state) {
                        return Str::limit($state, 30, '...');
                    })
                    ->tooltip(function ($state) {
                        return $state;
                    })
                    ->placeholder('---')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->reorderableColumns()
            ->defaultSort('date', 'desc')
            ->poll('10s')
            ->recordActions([
                // ViewAction::make(),
                EditAction::make()
                    ->slideover(),
                CustomAction::safeDelete(),
                Action::make('resend_whatsapp')
                    ->label('Resend WhatsApp')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'failed')
                    ->action(function ($record) {
                        dispatch(new SendCustomerMessageJob($record));
                        Notification::make()
                            ->title('WhatsApp Resend Triggered')
                            ->body("Message resend queued for {$record->name}")
                            ->success()
                            ->send();
                    }),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn($record): bool => $record->status === 'failed',
            )
            ->toolbarActions([
                BulkActionGroup::make([
                    CustomAction::safeBulkDelete(),
                    BulkAction::make('resend_whatsapp')
                        ->label('Resend WhatsApp')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                dispatch(new SendCustomerMessageJob($record));
                                Notification::make()
                                    ->title('WhatsApp Resend Triggered')
                                    ->body("Message resend queued for {$record->name}")
                                    ->success()
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }
}
