<?php


namespace Djunehor\Vtu\Concrete;


use Djunehor\Vtu\Contracts\VtuServiceInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;

class VTPass extends Vtu
{
    private $sandboxURL='https://sandbox.vtpass.com/api/';
    private $liveAPI='https://vtpass.com/api/';
    private $baseUrl;

    public function __construct($username = null, $password = null, $live = true)
    {
        $this->username = $username ?? config('laravel-vtu.vtpass.username');
        $this->password = $password ?? config('laravel-vtu.vtpass.password');
        $this->baseUrl = $live ? $this->liveAPI : $this->sandboxURL;

        $this->client = $this->getInstance();

    }

    /**
     * acceptable network: mtn, airtel, etisalat, glo
     * @param $amount
     * @param $mobileNumber
     * @param $mobileNetwork
     * @param null $callBackUrl
     * @return bool
     */
    public function buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl = null): bool
    {
        $headers = [
            "Authorization" => "Basic ".$this->username.$this->password
        ];
        $this->request = new Request('POST', $this->baseUrl."pay", $headers);

        try {
            $response = $this->client->send($this->request, [
                'form_params' => [
                    'request_id' => $requestId = Str::random(32),
                    'serviceID' => $mobileNetwork,
                    'amount' => $amount,
                    'phone' => $mobileNumber,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response['response_description'];
            $this->orderId = $requestId;

            return $response['code'] == '000' ? true : false;
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
    public function buyData($mobileNumber, $mobileNetwork, $dataPlan, $callBackUrl): bool
    {
        // TODO: Implement buyData() method.
    }


    public function queryOrder($orderId): bool
    {
        $headers = [
            "Authorization" => "Basic ".$this->username.$this->password
        ];
        $this->request = new Request('POST', $this->baseUrl."pay", $headers);

        try {
            $response = $this->client->send($this->request, [
                'form_params' => [
                    'request_id' => $requestId = Str::random(32),
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response['content'];

            return $response['code'] == '001' ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }

    public function variationCodes($serviceId): bool
    {
        $headers = [
            "Authorization" => "Basic ".$this->username.$this->password
        ];
        $this->request = new Request('GET', $this->baseUrl."service-variations", $headers);

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'serviceID' => $serviceId,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response['content'];

            return $response['content'] ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }

    public function verifySmartCard($smartCardNumber, $serviceId): bool
    {
        $headers = [
            "Authorization" => "Basic ".$this->username.$this->password
        ];
        $this->request = new Request('GET', $this->baseUrl."merchant-verify", $headers);

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'billersCode' => $smartCardNumber,
                    'serviceID' => $serviceId,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response['content'];

            return $response['code'] == '000' ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }

    public function payUtility($smartCardNumber, $cableTv, $package, $callbackUrl = null, $phone = null): bool
    {
        $headers = [
            "Authorization" => "Basic ".$this->username.$this->password
        ];
        $this->request = new Request('GET', $this->baseUrl."pay", $headers);

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'billersCode' => $smartCardNumber,
                    'serviceID' => $cableTv,
                    'variation_code' => $package,
                    'phone' => $phone,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response['content'];

            return $response['code'] == '000' ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }
}