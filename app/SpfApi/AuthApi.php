<?php
/**
 * Created by PhpStorm.
 * User: buicongdang
 * Date: 7/24/19
 * Time: 9:54 AM
 */

namespace App\SpfApi;

use GuzzleHttp\Client;

class AuthApi
{

    protected $clientId;
    protected $clientSecret;
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->clientId = config('shopify.spf_api_key');
        $this->clientSecret = config('shopify.spf_secret_key');

    }


    function verifyRequest(array $data): bool
    {
        $shop = $data['shop'] ?? null;
        $pattern = '/\A[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com\z/';
        if(!preg_match($pattern, $shop)){
            return false;
        }
        $tmp  = [];
        if (is_string($data)) {
            $each = explode('&', $data);
            foreach ($each as $e) {
                [$key, $val] = explode('=', $e);
                $tmp[$key] = $val;
            }
        } elseif (is_array($data)) {
            $tmp = $data;
        } else {
            return false;
        }

        // Timestamp check; 1 hour tolerance
        if (($tmp['timestamp'] - time() > 3600)) {
            return false;
        }

        if (array_key_exists('hmac', $tmp)) {

            // HMAC Validation
            $queries = array_intersect_key($tmp, [
                'code' => '',
                'shop' => '',
                'host' => '',
                'state' => '',
                'timestamp' => '',
            ]);
            ksort($queries);

            $queryString = http_build_query($queries);
            $match = $tmp['hmac'];
            $calculated = hash_hmac('sha256', $queryString, $this->clientSecret);
            return $calculated === $match;
        }


        return false;
    }


    /**
     * @param $shop_domain
     * @return string
     */
    function urlInstall(string $shop_domain): string
    {
        $client_id = $this->clientId;
        $scopes = implode(',', config('shopify.scope'));
        $redirect_uri = config('shopify.redirect_url');

        return "https://{$shop_domain}.myshopify.com/admin/oauth/authorize?client_id={$client_id}&scope={$scopes}&redirect_uri={$redirect_uri}";
    }

    /**
     * @param $code
     * @return array
     */
    function getAccessToken(string $shop, string $code) : array
    {

        try{
            $response = $this->client->request('POST', "https://{$shop}/admin/oauth/access_token.json",
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode([
                    'code' => $code,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                ])
            ]);
            return ['status' => true, 'data' => json_decode($response->getBody()->getContents(), true)];
        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }


    function getProducts(string $shop, string $access_token): array
    {
        try {
            $response = $this->client->request('GET', "https://{$shop}/admin/products.json",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-Shopify-Access-Token' => $access_token,
                    ],
                ]);
            return ['status' => true, 'data' => json_decode($response->getBody()->getContents(), true)];
        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}
