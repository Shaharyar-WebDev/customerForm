<?php

namespace App\Services;

use Exception;
use App\ApiChannel;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsappMessageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function send(Customer $customer, string $apiUrl, string $token, string $channel, string $template)
    {

        $response = match ($channel) {
            ApiChannel::ULTRA_MSG->value => self::sendViaUltraMsg($customer, $apiUrl, $token, $template),
            ApiChannel::WHAPI->value => self::sendViaWhapi($customer, $apiUrl, $token, $template),
        };

        if ($response->successful()) {
            $customer->update([
                'status' => 'sent',
                'sent_at' => now(),
                'error' => null,
            ]);
            Log::info("Message successfully sent to {$customer->name}");
        } else {
            $error = $response->body();
            $customer->update([
                'status' => 'failed',
                'error' => $error,
            ]);
            throw new Exception("Message failed: {$error}");
        }
    }

    public static function sendViaUltraMsg(Customer $customer, string $apiUrl, string $token, string $template)
    {
        $to = "+92{$customer->phone_number}";

        $messageBody = str_replace(
            ['{name}', '{phone}', '{id}'],
            [$customer->name, $customer->phone_number, $customer->id],
            $template
        );

        $response = Http::asForm()->post($apiUrl, [
            'token' => $token,
            'to' => $to,
            'body' => $messageBody,
        ]);

        return $response;
    }

    public static function sendViaWhapi(Customer $customer, string $apiUrl, string $token, string $template)
    {
        $to = "+92{$customer->phone_number}";

        $messageBody = str_replace(
            ['{name}', '{phone}', '{id}'],
            [$customer->name, $customer->phone_number, $customer->id],
            $template
        );

        $response = Http::withToken($token)
            ->post($apiUrl . '/messages/text', [
                'to' => $to,
                'body' => $messageBody,
            ]);

        return $response;
    }
}
