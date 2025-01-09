<?php

// app/Services/AdobeSignService.php
namespace App\Services;

use GuzzleHttp\Client;

class AdobeSignService
{
    protected $client;
    protected $baseUri = 'https://api.adobe.io/';
    protected $apiKey;

    public function __construct()
    {
        $this->baseUri = config('services.adobe_sign.base_uri');
        $this->apiKey = config('services.adobe_sign.api_key');

        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function sendAgreement($templateId, $recipients, $documentName)
    {
        $response = $this->client->post('/agreements', [
            'json' => [
                'documentCreationInfo' => [
                    'templateId' => $templateId,
                    'name' => $documentName,
                    'recipientsList' => [
                        [
                            'email' => $recipients,
                            'role' => 'SIGNER', // Role can vary
                        ],
                    ],
                    'signatureType' => 'ESIGN',
                    'state' => 'IN_PROCESS',
                ],
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getAgreementStatus($agreementId)
    {
        $response = $this->client->get("/agreements/{$agreementId}");
        return json_decode($response->getBody()->getContents(), true);
    }

    // Cancel Agreement
    public function cancelAgreement($agreementId)
    {
        $response = $this->client->put("/agreements/{$agreementId}/state", [
            'json' => [
                'state' => 'CANCELLED',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
