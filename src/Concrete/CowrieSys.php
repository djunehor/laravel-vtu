<?php

namespace Djunehor\Vtu\Concrete;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class CowrieSys extends Vtu
{
    private $baseUrl ='https://api.cowriesys.com';

    public function __construct($clientId = null, $clientKey = null)
    {
        $this->username = $clientId ?? config('laravel-vtu.cowriesys.client_id');
        $this->password = $clientKey ?? config('laravel-vtu.cowriesys.client_key');

        $this->client = $this->getInstance();

    }

    function sign($message) {
        return base64_encode(hash_hmac('sha256', $message, base64_decode($this->password), true));
    }

    public function buyAirtime($amount, $mobileNumber, $mobileNetwork, $callbackUrl = null) : bool {
        $nonce = uniqid();
        $queryString = '?net='.$mobileNetwork.'&msisdn='.$mobileNumber.'&amount='.$amount.'&xref='.$nonce;
        $signature = $this->sign($nonce.$queryString);
        $headers = [
            'ClientId: '.$this->username,
            'Signature: '.$signature,
            'Nonce: '.$nonce
        ];
        $api = '/airtime/Credit';
        $this->request = new Request('GET', $this->baseUrl.$api, $headers);


        try {
            $response = $this->client->send($this->request, [
                'query' => [
                    'net' => $mobileNetwork,
                    'msisdn' => $mobileNumber,
                    'amount' => $amount,
                    'xref' => $nonce,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response;

            return $response ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }

    public function buyData($amount, $mobileNumber, $mobileNetwork, $callbackUrl = null) : bool {
        $nonce = uniqid();
        $queryString = '?net='.$mobileNetwork.'&msisdn='.$mobileNumber.'&amount='.$amount.'&xref='.$nonce;
        $signature = $this->sign($nonce.$queryString);
        $headers = [
            'ClientId: '.$this->username,
            'Signature: '.$signature,
            'Nonce: '.$nonce
        ];
        $api = '/data/Credit';
        $this->request = new Request('GET', $this->baseUrl.$api, $headers);


        try {
            $response = $this->client->send($this->request, [
                'query' => [
                    'net' => $mobileNetwork,
                    'msisdn' => $mobileNumber,
                    'amount' => $amount,
                    'xref' => $nonce,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response;

            return $response ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function payUtility($smartCardNumber, $cableTv, $package, $callBackUrl): bool
    {
        return false;
    }
}