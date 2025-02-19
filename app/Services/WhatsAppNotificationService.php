<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected $tokenEndpoint = 'https://service-chat.qontak.com/api/open/v1/oauth/token';
    protected $broadcastEndpoint = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';

    protected $username = "it.programmer2@motasaindonesia.co.id";
    protected $password = "Motasa@123!";
    protected $clientId = "RRrn6uIxalR_QaHFlcKOqbjHMG63elEdPTair9B9YdY";
    protected $clientSecret = "Sa8IGIh_HpVK1ZLAF0iFf7jU760osaUNV659pBIZR00";

    /**
     * Dapatkan token akses dari API WhatsApp.
     */
    public function getAccessToken()
    {
        $response = Http::post($this->tokenEndpoint, [
            "username"      => $this->username,
            "password"      => $this->password,
            "grant_type"    => "password",
            "client_id"     => $this->clientId,
            "client_secret" => $this->clientSecret,
        ]);

        return $response['access_token'] ?? null;
    }

    /**
     * Kirim pesan WhatsApp.
     *
     * @param string $toName
     * @param string $toNumber
     * @param string $messageTemplateId
     * @param string $channelIntegrationId
     * @param array  $parameters
     * @return mixed
     */
    public function sendMessage($toName, $toNumber, $messageTemplateId, $channelIntegrationId, array $parameters)
    {
        $token = $this->getAccessToken();
        Log::info("Token : {$token}");
        if (!$token) {
            return false;
        }

        $response = Http::withToken($token)->post($this->broadcastEndpoint, [
            "to_name"                => $toName,
            "to_number"              => $toNumber,
            "message_template_id"    => $messageTemplateId,
            "channel_integration_id" => $channelIntegrationId,
            "language"               => ["code" => "id"],
            "parameters"             => $parameters,
        ]);

        Log::info("Response : {$response}");

        return $response->json();
    }
}
