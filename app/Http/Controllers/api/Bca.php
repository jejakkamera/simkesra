<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BcaApiService;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use DateTime;

class Bca extends Controller
{
    //
    protected $bcaApiService;
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $privatekey;
    protected $apiUrl;

    public function __construct(BcaApiService $bcaApiService)
    {
        $this->bcaApiService = $bcaApiService;
        $this->client = new Client();
        $this->clientId = env('BCA_CLIENT_ID');
        $this->clientSecret = env('BCA_CLIENT_SECRET');
        $this->privatekey = "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCugPTyjFhr4BCOGKT9WC4YV+JR/sZrc3F/+M6U0fLmqHIgqvnqP2VNUPzIrH1CbSxx4x+nuXILo83JCfKneNmPXgXn0WwgLhKbV20RNnbU4NX382j5jNWPR6j/XMy1KKb0dbq6RVRI+hdrbrXxcFu3AU3iqo6SN1xksayfkph2vtIaeNkp78oPJmwYc9BJlcBNcoNSlypTnrPHaS74B2W/+906t6brSZaDo9LLyLcU4jU1eCg0mh0P2FV8dm7xLCR6kEB8oKoN+QcvYK5Uq/M5VIeOkPodsALBn+WZq8QXz1L+eF3E3uxUvXbaPOWyxsTH7r8h38NHZBLrrPxzpBZbAgMBAAECggEBAJM0u+1kES8nPC/ygDQrT+8/K+9Jgi6gmy1+QR5voX2MuYHvWZWO1zEK0cOjJqODn0uPcmO134wMKYufNbCrFLSJd2fgVVssIowiiyJLFKFt7XIWRwbFFF6i7+BDc3ibC4QLUHLUC07okhsRsLA0vVm64u4EPPMBepKDA4E1VCJOXwY+KqWddL6mI1fhJ/jpy/Jk6+CmDpwmQBHksSH8YhNCO4v9J/jg3yDFIpO6b4nkm22nAYCJotiN3zpq2NRWq6L4MktwsOg4gS/Are7Hhn8+q9cpFY7sfbau9vNLiIFyh60DPmEj51bsYB9wWkFuNJfKlvzoslW7NofVMF8TXykCgYEA5P/UGc5KD+yghbA0SoY3GWicR/hjcUlJ7x0Ey7pJEo6aODdzCgATiL7UvkGISbufVuhWQQs5ddHvAGb5dsfmKKMDxWyr2VywBHktWm8S6tl5i4PhSbdxmdQmt8iKphgDS5osk9S31qYhS3fedpeWRrwvCsjlP0nTsPkZqGhqQCcCgYEAwxQ4V5j+D/wamddKgvFdtmtTwWw0t5lroNQ/sL+zvT+A0E/gkfG48N8Fb8kibeACclfra1CmC4GbVhxPAWE6BL5azY16CgdFylyB4cBtHYOTH2RjwQOGXG3QsFZIgpylwI5EikoV/ujqJKI+wNFitLk0LFF1xA1bNEMoW2Qs5K0CgYEAyI2VABS+oDuoSe0Lnsj0sHgBhrZuwORir9tGO/Yl0O66+cj2Iyf186jNQOK7rXd4EPDhuY1PAXSeUEfe6rwfQi+iOeD2kCKwtPo7Uhw9ARj9bcZOI/VYtFQspIApjVUybZ/UspN1fbN5LVMJresMXV6qBFP1EfxiPXerlOX9R7kCgYBL9R2TDiEuvCznZeq/XZftpZCxKZ7FNlmv/7Tk71/e+lD/y3pXmVU3hL8rLZfYTHbnatBhsr9Uj5yaRN+GXAMpQa09iC7SpM5J4wc3jaNu8IJioMYuq16vspqlbpNBOBlaBosthXdXD/3LUdk5Xs4eLFbiQr2mHsU6bkPSggjw6QKBgFxPZBbyysvzCAYlmqZ/fRnNKaLurOO7BP1LVP8IAVZzuVHhrYld9DPWLpNxT0+/LjlAZlpIjfOXTX+geCKVNJtMfGcq4EJXPChUyy4Ps8OlR94Lyat3qh5wCn+0iBbcZavqMitNodL1zV1uYQw4cLT4l55akFL1fB1u3PQJsNpY";
    }

    //================Validate================================

    public function validateOauthSignature($client_id, $iso_time, $signature)
    {
        $is_valid = false;

        // Path ke public key di storage
        $keyPath = storage_path('bca.pem');

        // Baca public key dari file
        if (!file_exists($keyPath)) {
            throw new \Exception('Public key file not found at ' . $keyPath);
        }

        $public_key = file_get_contents($keyPath);

        $algo = "SHA256";
        $dataToSign = $client_id . "|" . $iso_time;

        // Verifikasi signature menggunakan public key
        $is_valid = openssl_verify($dataToSign, base64_decode($signature), $public_key, $algo);

        if ($is_valid == 1) {
            $is_valid = true;
        } else {
            $is_valid = false;
        }

        return $is_valid;
    }

    protected function validateToken(Request $request)
    {
        $accessToken = $request->header('Authorization');

        // Validasi format header
        if (!$accessToken || !str_starts_with($accessToken, 'Bearer ')) {
            return response()->json([
                'responseCode' => '4012401',
                'responseMessage' => 'Invalid Token (B2B)'
            ], 401);
        }

        // Ambil token dari header
        $token = substr($accessToken, 7);

        // Verifikasi token di cache
        if (!Cache::has($token)) {
            return response()->json([
                'responseCode' => '4012401',
                'responseMessage' => 'Token Expired or Invalid'
            ], 401);
        }

        return true;
    }

    protected function validateRequest(Request $request)
    {
        $timestamp = $request->header('X-TIMESTAMP');
        $clientKey = $request->header('X-CLIENT-KEY');
        $signature = $request->header('X-SIGNATURE');

        if (!$timestamp) {
            return response()->json([
                'responseCode' => '4007301',
                'responseMessage' => 'Invalid field format [X-TIMESTAMP]',
            ], 400);
        }

        if (!$this->isValidISO8601($timestamp)) {
            return response()->json([
                'responseCode' => '4007301',
                'responseMessage' => 'Invalid field format [X-TIMESTAMP]',
            ], 400);
        }

        if (!$clientKey || !$signature) {
            return response()->json([
                'responseCode' => '4007302',
                'responseMessage' => 'Invalid mandatory field [X-CLIENT-KEY]',
            ], 400);
        }

        $clientId = $this->clientId;
        if ($clientKey !== $clientId) {
            return response()->json([
                'responseCode' => '4017300',
                'responseMessage' => 'Unauthorized. [Unknown client]',
            ], 401);
        }

        $validate = $this->validateOauthSignature($clientKey, $timestamp, $signature);

        if ($validate == false) {
            return response()->json([
                'responseCode' => '4017300',
                'responseMessage' => 'Unauthorized. [Signature]',
            ], 401);
        }

        return true; // Validasi berhasil
    }

    function isValidISO8601($timestamp)
    {
        $date = DateTime::createFromFormat(DateTime::ATOM, $timestamp);
        return $date && $date->format(DateTime::ATOM) === $timestamp;
    }

    public function generateApiToken($client_id)
    {
        // Generate token unik
        $token = hash('sha256', Str::random(60));

        // Waktu kadaluarsa (900 detik = 15 menit)
        $expiresInSeconds = 900;

        // Simpan token ke cache dengan durasi 900 detik
        Cache::put($token, ['client_id' => $client_id], $expiresInSeconds);

        return [
            'token' => $token,
            'expires_at' => now()->addSeconds($expiresInSeconds)->toDateTimeString()
        ];
    }

    private function hashbody($body)
    {
        if (empty($body)) {
            $body = '';
        } else {
            //$toStrip = [" ", "\r", "\n", "\t"];
            //$body = str_replace($toStrip, '', $body);
        }
        return strtolower(hash('sha256', $body));
    }

    private function getRelativeUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (empty($path)) {
            $path = '/';
        }

        $query = parse_url($url, PHP_URL_QUERY);
        if ($query) {
            parse_str($query, $parsed);
            ksort($parsed);
            $query = '?' . http_build_query($parsed);
        }
        $formatedUrl = $path . $query;
        return $formatedUrl;
    }

    public function generateServiceSignature($client_secret, $method, $url, $auth_token, $isoTime, $bodyToHash = [])
    {
        $hash = hash("sha256", "");
        if (is_array($bodyToHash)) {
            $encoderData = json_encode($bodyToHash, JSON_UNESCAPED_SLASHES);
            $hash = $this->hashbody($encoderData);
        }

        $stringToSign = $method . ":" . $this->getRelativeUrl($url) . ":" . $auth_token . ":" . $hash . ":" . $isoTime;
        $signature = base64_encode(hash_hmac('sha512', $stringToSign, $client_secret, true));
        return $signature;
    }

    public function validateServiceSignature(Request $request)
    {
        $client_secret = $this->clientSecret;
        $method = $request->method();
        $url = parse_url($request->url());
        $host = $url['host'];
        $auth_token = $request->header('Authorization');
        $auth_token = substr($auth_token, 7);
        $isoTime = $request->header('X-TIMESTAMP');
        $signature = $request->header('X-SIGNATURE');
        $bodyToHash = $request->json()->all();
        
        $is_valid = false;
        $signatureStr = $this->generateServiceSignature($client_secret, $method, $host, $auth_token, $isoTime, $bodyToHash);
        if (strcmp($signatureStr, $signature) == 0) {
            $is_valid = true;
        }
        return $is_valid;
    }

    //====================Logic============================

    public function inquiry(Request $request)
    {

        // $validateToken = $this->validateToken($request);
        // if ($validateToken !== true) {
        //     return $validateToken;
        // }

        $validationResponse = $this->validateServiceSignature($request);

        if ($validationResponse !== true) {
            return response()->json([
                'responseCode' => '4007301',
                'responseMessage' => 'Invalid field format [clientId/clientSecret/grantType]'
            ], 400);
            // return $validationResponse;
        }

        $partnerServiceId = $request->input('partnerServiceId');
        $customerNo = $request->input('customerNo');
        $virtualAccountNo = $request->input('virtualAccountNo');
        $trxDateInit = $request->input('trxDateInit');
        $channelCode = $request->input('channelCode');
        $inquiryRequestId = $request->input('inquiryRequestId');

        if ($virtualAccountNo === '15395000002401000') {

            $response = [
                "responseCode" => "2002400",
                "responseMessage" => "Successful",
                "virtualAccountData" => [
                    "inquiryStatus" => "00",
                    "inquiryReason" => [
                        "english" => "Success",
                        "indonesia" => "Sukses"
                    ],
                    "partnerServiceId" => $partnerServiceId,
                    "customerNo" => $customerNo,
                    "virtualAccountNo" => $virtualAccountNo,
                    "virtualAccountName" => "SD Yaperos Test",
                    "inquiryRequestId" => $inquiryRequestId,
                    "virtualAccountEmail" => "",
                    "virtualAccountPhone" => "",
                    "totalAmount" => [
                        "value" => "100000.00",
                        "currency" => "IDR"
                    ],
                    "subCompany" => "00000",
                    "billDetails" => [],  // Empty array as no bill details provided
                    "freeTexts" => [
                        [
                            "english" => "Payment for SD Yaperos Test",
                            "indonesia" => "Pembayaran SD Yaperos Test"
                        ]
                    ],
                    "virtualAccountTrxType" => "C", // Indicates credit transaction type
                    "feeAmount" => [
                        "value" => "",
                        "currency" => ""
                    ],
                    "additionalInfo" => [] // Empty object as no additional info provided
                ]
            ];

            return response()->json($response);
        } else {
            return response()->json([
                "responseCode" => "4042412",
                "responseMessage" => "Bill not found",
                "virtualAccountData" => [
                    "inquiryStatus" => "01",
                    "inquiryReason" => [
                        "english" => "Bill not found",
                        "indonesia" => "Tagihan tidak ditemukan"
                    ],
                    "partnerServiceId" => $partnerServiceId,
                    "customerNo" => "",
                    "virtualAccountNo" => "",
                    "virtualAccountName" => "",
                    "inquiryRequestId" => "",
                    "totalAmount" => [
                        "value" => "",
                        "currency" => ""
                    ],
                    "subCompany" => "",
                ],
            ]);
        }
        // echo "Inquiry";
    }

    public function getAccessToken(Request $request) //Asymmetric Encryption
    {
        $validationResponse = $this->validateRequest($request);
        if ($validationResponse !== true) {
            return $validationResponse;
        }

        // dd($request->json()->all());
        $data = $request->json()->all();

        if (!isset($data['grantType']) || $data['grantType'] !== 'client_credentials') {
            return response()->json([
                'responseCode' => '4007301',
                'responseMessage' => 'Invalid field format [clientId/clientSecret/grantType]', // Invalid field format [clientId/clientSecret/grantType]
            ], 400);
        }

        $timestamp = $request->header('X-TIMESTAMP');
        $clientKey = $request->header('X-CLIENT-KEY');
        $signature = $request->header('X-SIGNATURE');

        $validate = $this->validateOauthSignature($clientKey, $timestamp, $signature);

        if ($validate == false) {
            return response()->json([
                'responseCode' => '4017300',
                'responseMessage' => 'Unauthorized. [Signature]',
            ], 401);
        } else {
            $token = $this->generateApiToken($clientKey);
            return response()->json([
                'responseCode' => '2007300',
                'responseMessage' => 'Successful',
                'accessToken' => $token['token'],
                'tokenType' => 'bearer',
                'expiresIn' => 900
            ]);
        }
    }
}
