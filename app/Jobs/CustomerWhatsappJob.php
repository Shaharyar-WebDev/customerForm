<?php

namespace App\Jobs;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Customer;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\WhatsappMessageService;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\RequestException;

class CustomerWhatsappJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $tries = 3;
    public Customer $customer;
    public ?string $api_url;
    public ?string $api_token;
    public ?string $message_template;
    public ?string $api_channel;

    public function __construct(Customer $customer)
    {
        $settings = app(GeneralSettings::class);
        $this->customer = $customer;
        $this->api_url = $settings->api_url;
        $this->api_token = $settings->api_token;
        $this->message_template = $settings->message_template;
        $this->api_channel = $settings->api_channel;

        if (empty($this->api_url) || empty($this->api_token) || empty($this->api_channel)) {
            foreach (User::all() as $user) {
                Notification::make()
                    ->title('Api Configuration Missing')
                    ->body('API URL or API Token is not set in the settings.')
                    ->sendToDatabase($user);
            }

            // Optional: mark customer as failed immediately
            $customer->update([
                'status' => 'failed',
                'error' => 'API URL or Token not set',
            ]);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customer = $this->customer;

        if (empty($this->api_url) || empty($this->api_token) || empty($this->api_channel)) {
            Log::warning("Skipping WhatsApp message for customer ID {$customer->name}: API settings missing.");
            return;
        }

        if ($customer->status === 'sent') {
            Log::warning("Skipping WhatsApp message for customer ID {$customer->name}: Its already sent.");
            return;
        }

        $customer->update([
            'status' => 'pending',
            'error' => null,
        ]);

        Log::info("SendWhatsAppMessage job started for customer ID: {$customer->id} ({$customer->name})");

        $apiUrl = $this->api_url;
        $token = $this->api_token;
        $template = $this->message_template ?? "Hello {name}!";
        $channel = $this->api_channel;

        WhatsappMessageService::send($customer, $apiUrl, $token, $channel, $template);
    }
}
