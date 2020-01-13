<?php


namespace Djunehor\Vtu\Concrete;


use Djunehor\Vtu\Contracts\VtuServiceInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;

class MobileAirtime extends Vtu
{
    private $baseUrl = 'https://mobileairtimeng.com/';
    private $orderId = 0;


    public function __construct($username = null, $password = null)
    {
        $this->username = $username ?? config('laravel-vtu.mobile_airtime.username');
        $this->password = $password ?? config('laravel-vtu.mobile_airtime.password');

        $this->client = $this->getInstance();
    }

    /**
     * @inheritDoc
     */
    public function buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl): bool
    {

        $this->request = new Request('GET', $this->baseUrl . "/httpapi");

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'userid' => $this->username,
                    'pass' => $this->password,
                    'amt' => $amount,
                    'network' => $mobileNetwork,
                    'phone' => $mobileNumber,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response;

            return $this->response == '100' ? true : false;
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
        $this->request = new Request('GET', $this->baseUrl . "httpapi/datashare");

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'userid' => $this->username,
                    'pass' => $this->password,
                    'datasize' => $dataPlan,
                    'network' => $mobileNetwork,
                    'phone' => $mobileNumber,
                    'user_ref' => Str::random(16),
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response;
            $this->orderId = $response['batchno'] ?? 0;

            return $this->response['code']  == '100' ? true : false;
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
        //Todo: Implement utility bill payment
        return false;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function queryOrder($orderId)
    {
        $this->request = new Request('GET', $this->baseUrl . "/client/status");

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'userid' => $this->username,
                    'pass' => $this->password,
                    'tid' => $orderId,

                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = explode('|', $response);

            return $this->response ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;

        }
    }
}