<?php

namespace App\Jobs;

use Exception;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCustomerMessageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $tries = 3;
    public Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customer = $this->customer;

        $customer->update([
            'status' => 'pending',
            'error' => null,
        ]);

        try {
            Log::info("SendWhatsAppMessage job started for customer ID: {$customer->id} ({$customer->name})");

            $apiUrl = config('services.ultramsg.api_url');
            $token = config('services.ultramsg.token');

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

        } catch (Exception $e) {
            $customer->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
            throw $e; // triggers retry if queue is configured
        }
    }
}
