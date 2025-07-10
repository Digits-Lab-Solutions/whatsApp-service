<?php

namespace Digitslab\WhatsAppService;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public static function send(array $payload): array
    {
        $endpoint = config('whatsapp.endpoint');
        $token = config('whatsapp.api_token');

        $finalPayload = array_merge(['token' => $token], $payload);

        try {
            $response = Http::timeout(15)->post($endpoint, $finalPayload);

            if ($response->successful() && $response->json('status') === 'success') {
                return [
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'response' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('errors.0.message') ?? 'Unknown error',
                'response' => $response->json()
            ];
        } catch (\Exception $e) {
            return [ 
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function sendTemplateMessage(array $data)
    {
        $token = config('whatsapp.token');
        $endpoint = config('whatsapp.endpoint', 'https://msgup.in/api/wpbox/sendtemplatemessage');

        $response = Http::post($endpoint, [
            'token' => $token,
            'phone' => $data['phone'],
            'template_name' => $data['template_name'],
            'template_language' => $data['template_language'] ?? 'en',
            'components' => $data['components'],
        ]);

        return $response->json();
    }
}
