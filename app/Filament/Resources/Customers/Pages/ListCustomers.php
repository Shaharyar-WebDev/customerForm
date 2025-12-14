<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Exports\CustomerExport;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Customers\CustomerResource;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver(),
            CustomAction::excelExporter(
                'export_customers',
                'Export Customers',
                CustomerExport::class,
                'customers_export'
            ),
        ];
    }
}
