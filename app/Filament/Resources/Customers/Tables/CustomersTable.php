<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('phone_number')
                    ->label('WhatsApp No.')
                    ->prefix('+92')
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

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->toggleable()
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
            ->recordActions([
                // ViewAction::make(),
                EditAction::make()
                    ->slideover(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
