<?php

namespace App\Jobs;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Customer;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\RequestException;

class SendCustomerMessageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $tries = 3;
    public Customer $customer;
    public ?string $api_url;
    public ?string $api_token;

    public function __construct(Customer $customer)
    {
        $settings = app(GeneralSettings::class);
        $this->customer = $customer;
        $this->api_url = $settings->api_url;
        $this->api_token = $settings->api_token;

        if (empty($this->api_url) || empty($this->api_token)) {
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

        if (empty($this->api_url) || empty($this->api_token)) {
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

        $to = "+92{$customer->phone_number}";

        $messageBody = "Hello {$customer->name}, thanks for visiting!";

        $response = Http::asForm()->post($apiUrl, [
            'token' => $token,
            'to' => $to,
            'body' => $messageBody,
        ]);

        if ($response->successful()) {
            $customer->update([
                'status' => 'sent',
                'sent_at' => now(),
                'error' => null,
            ]);
            Log::info("Message successfully sent to {$to}");
        } else {
            $error = $response->body();
            $customer->update([
                'status' => 'failed',
                'error' => $error,
            ]);
            throw new Exception("UltraMsg failed: {$error}");
        }
    }
}
