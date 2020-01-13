<?php


namespace Djunehor\Vtu\Concrete;


use Djunehor\Vtu\Contracts\VtuServiceInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;

class FpeVtu extends Vtu
{
    private $baseUrl = 'https://fpevtu.com/';
    private $orderId = 0;


    public function __construct($username = null, $password = null)
    {
        $this->username = $username ?? config('laravel-vtu.fpe_vtu.username');
        $this->password = $password ?? config('laravel-vtu.fpe_vtu.password');

        $this->client = $this->getInstance();
    }

    /**
     * @inheritDoc
     */
    public function buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl): bool
    {

        $this->request = new Request('GET', $this->baseUrl . "client/httpvtu");

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'userid' => $this->username,
                    'pass' => $this->password,
                    'amount' => $amount,
                    'network' => $mobileNetwork,
                    'phone' => $mobileNumber,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = explode('|', $response);

            return $this->response[0] == '1000' ? true : false;
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
        $this->request = new Request('GET', $this->baseUrl . "client/http");

        try {
            $response = $this->client->send($this->request, [
                'query_params' => [
                    'userid' => $this->username,
                    'pass' => $this->password,
                    'datasize' => $dataPlan,
                    'network' => $mobileNetwork,
                    'phone' => $mobileNumber,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = explode('|', $response);
            $this->orderId = $this->response[1] ?? 0;

            return $this->response[0] == '1000' ? true : false;
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
        // TODO: Implement payUtility() method.
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