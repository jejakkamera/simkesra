<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BcaApiService
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $apiUrl;

    public function __construct()
    {
        // Inisialisasi variabel dan Guzzle HTTP client
        $this->client = new Client();
        $this->clientId = env('BCA_CLIENT_ID');
        $this->clientSecret = env('BCA_CLIENT_SECRET');
        $this->apiUrl = env('BCA_API_URL') . '/openapi/' . env('BCA_API_VERSION');
    }

    // Fungsi untuk menghasilkan Access Token
    public function generateAccessToken()
    {
        $timestamp = now()->format('Y-m-d\TH:i:sP');
        $stringToSign = "{$this->clientId}|{$timestamp}";

        $signature = $this->generateHmacSignature($stringToSign);

        $headers = [
            'X-TIMESTAMP' => $timestamp,
            'X-CLIENT-KEY' => $this->clientId,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json',
        ];

        $body = [
            'grantType' => 'client_credentials',
        ];

        try {
            $response = $this->client->post("{$this->apiUrl}/access-token/b2b", [
                'headers' => $headers,
                'json' => $body,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('BCA Access Token Generation Failed: ' . $e->getMessage());
            return null;
        }
    }

    // Fungsi untuk menghasilkan tanda tangan HMAC
    private function generateHmacSignature($stringToSign)
    {
        return base64_encode(hash_hmac('sha512', $stringToSign, $this->clientSecret, true));
    }

    // Fungsi untuk melakukan inquiry transaksi
    public function inquiryTransaction($accessToken, $relativeUrl, $requestBody)
    {
        $timestamp = now()->format('Y-m-d\TH:i:sP');
        $minifiedBody = json_encode($requestBody);
        $hash = hash('sha256', $minifiedBody);
        $stringToSign = "POST:{$relativeUrl}:{$accessToken}:{$hash}:{$timestamp}";
        $signature = $this->generateHmacSignature($stringToSign);

        $headers = [
            'Authorization' => "Bearer {$accessToken}",
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json',
        ];

        try {
            $response = $this->client->post("{$this->apiUrl}{$relativeUrl}", [
                'headers' => $headers,
                'json' => $requestBody,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('BCA Transaction Inquiry Failed: ' . $e->getMessage());
            return null;
        }
    }
}
