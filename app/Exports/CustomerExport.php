<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::get();
    }

    public function headings(): array
    {
        return [
            'date',
            'name',
            'phone_number',
            'frequently_purchased_items',
            'visit_frequency',
            'message status',
            'error',
            'sent_at',
            'category',
            'remarks'
        ];
    }

    public function map($customer): array
    {
        return [
            Carbon::parse($customer->date)->format(app_date_format()),
            $customer->name,
            "0$customer->phone_number",
            $this->formatItems($customer->frequently_purchased_items),
            $customer->visit_frequency,
            $customer->status,
            $customer->error,
            $customer->sent_at,
            $customer->category,
            $customer->remarks
        ];
    }

    protected function formatItems(?array $items): string
    {
        if (empty($items)) {
            return '';
        }

        return collect($items)
            ->filter()
            ->map(function ($values, $key) {
                $value = is_array($values)
                    ? implode(', ', $values)
                    : $values;

                return ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
            })
            ->implode(' | ');
    }
}
