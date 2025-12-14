<?php

namespace App\Models;

use App\Jobs\CustomerWhatsappJob;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class Customer extends Model
{
    protected $fillable = [
        'date',
        'name',
        'phone_number',
        'frequently_purchased_items',
        'visit_frequency',
        'status',
        'error',
        'sent_at',
        'category',
        'remarks'
    ];

    // Optional: cast JSON field to array automatically
    protected $casts = [
        'frequently_purchased_items' => 'array',
    ];

    public static function booted()
    {
        static::created(function ($customer) {
            dispatch(new CustomerWhatsappJob($customer))->afterCommit();
        });
    }
}
