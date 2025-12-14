<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;
use App\Settings\GeneralSettings;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
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
                                        TagsInput::make('frequently_purchased_items.fusing')
                                            ->label('Fusing')
                                            ->reorderable()
                                            ->placeholder('Qty or info'),
                                        TagsInput::make('frequently_purchased_items.elastic')
                                            ->label('Elastic')
                                            ->reorderable()
                                            ->placeholder('Qty or info'),
                                        TagsInput::make('frequently_purchased_items.sewing_thread')
                                            ->label('Sewing Thread')
                                            ->reorderable()
                                            ->placeholder('Qty or info'),
                                        TagsInput::make('frequently_purchased_items.buttons')
                                            ->label('Buttons')
                                            ->reorderable()
                                            ->placeholder('Qty or info'),
                                        TagsInput::make('frequently_purchased_items.accessories')
                                            ->label('Accessories')
                                            ->reorderable()
                                            ->placeholder('Qty or info'),
                                        TagsInput::make('frequently_purchased_items.others')
                                            ->label('Others')
                                            ->reorderable()
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
                                'other' => 'Other'
                            ])
                            ->placeholder('Select customer category')
                            ->required(),

                        Textarea::make('remarks')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
