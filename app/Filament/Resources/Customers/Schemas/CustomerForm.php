<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;
use App\Settings\GeneralSettings;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        $generalSettings = app(GeneralSettings::class);

        return $schema
            ->components([
                Section::make("{$generalSettings?->site_name} Customer Survey")
                    ->columnSpanFull()
                    ->description('Fill out the details below to register the customer and send them the community link.')
                    ->columns(2) // make 2-column layout for better spacing
                    ->schema([
                        // Customer Name
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Customer Name')
                                    ->placeholder('Enter full name')
                                    ->required(),
                                // WhatsApp Number
                                TextInput::make('phone_number')
                                    ->label('Customer WhatsApp No.')
                                    ->placeholder('e.g. 923001234567')
                                    ->prefix('+92')
                                    ->required(),
                                DatePicker::make('date')
                                    ->required()
                                    ->columnSpanFull()
                                    ->default(now()),
                            ]),
                        // Frequently Purchased Items in Card with Grid
                        Section::make()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('frequently_purchased_items.fusing')
                                            ->label('Fusing')
                                            ->placeholder('Qty or info'),
                                        TextInput::make('frequently_purchased_items.elastic')
                                            ->label('Elastic')
                                            ->placeholder('Qty or info'),
                                        TextInput::make('frequently_purchased_items.sewing_thread')
                                            ->label('Sewing Thread')
                                            ->placeholder('Qty or info'),
                                        TextInput::make('frequently_purchased_items.buttons')
                                            ->label('Buttons')
                                            ->placeholder('Qty or info'),
                                        TextInput::make('frequently_purchased_items.accessories')
                                            ->label('Accessories')
                                            ->placeholder('Qty or info'),
                                        TextInput::make('frequently_purchased_items.others')
                                            ->label('Others')
                                            ->placeholder('Qty or info'),
                                    ])
                            ])
                            ->columnSpan(2)
                            ->heading('Frequently Purchased Items')
                            ->description('Enter the items the customer frequently purchases.'),

                        // Visit Frequency
                        Select::make('visit_frequency')
                            ->label('Visit Frequency')
                            ->options([
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                '15_days' => 'Every 15 Days',
                                'monthly' => 'Monthly',
                            ])
                            ->placeholder('Select visit frequency')
                            ->required(),

                        // Customer Category
                        Select::make('category')
                            ->label('Customer Category')
                            ->options([
                                'distributor' => 'Distributor / Supplier',
                                'retailer' => 'Retailer',
                                'tailor' => 'Tailor',
                            ])
                            ->placeholder('Select customer category')
                            ->required(),
                    ])
            ]);
    }
}
