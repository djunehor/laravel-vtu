<?php
/**
 * Created by PhpStorm.
 * User: Djunehor
 * Date: 1/22/2019
 * Time: 9:36 AM.
 */

namespace Djunehor\Vtu\Concrete;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class VoiceAndText extends Vtu
{
    private $baseUrl = 'https://www.voiceandtext.com/portal/vtu_api.php';
    private $orderId;
    private const BUY_AIRTIME = 'APIBuyAirTime';
    private const BUY_DATA = 'APIBuyDataPlan';
    private const PAY_UTILITY = 'APIBuyCableTv';
    private const QUERY_ORDER = 'APIQueryTransaction';
    private const CANCEL_ORDER = 'APICancelTransaction';

    private $statusCodes = [
        'MISSING_MOBILENETWORK' => 'Mobile network is empty',
        'MISSING_AMOUNT' => 'Amount is empty',

        'INVALID_ AMOUNT' => 'Amount is not valid',

        'INVALID_AIRTIME_AMOUNT' =>
            'Minimum amount is 100 and Minimum amount is 50,000',


        'INVALID_RECIPIENT' =>
            'An invalid mobile phone number was entered',

        'INSUFICINET_FUND' =>
            'Insufficient user wallet balance',


        'INVALID_SERVER_RESPONSE' =>
            'Unable to process request',
        'SERVER_ERROR' =>
            'Server downtime',


        'INVALID_COMMAND' =>
            'The command parameter is not valid',

        'INVALID_API_ KEY' =>
            'API Key is wrong',
        'ORDER_RECEIVED' =>
'Your order has been received',
        'MISSING_DATAPLAN'=>
'Data plan is empty',

'INVALID_DATAPLAN'=>
'Data plan is not valid',


'INVALID_SMARTCARDNO'=>
'An invalid smartcard number was entered',
        'MISSING_CABLETV'=>
'The URL format is not valid.',

'MISSING_PACKAGE'=>
'Package field is empty',


'PACKAGE_NOT_AVAILABLE'=>
'Selected package is not currently available',

    ];

    /**
     * Class Constructor.
     * @param null $token
     */
    public function __construct($token = null)
    {

        $this->username = isset($token) ? $token : config('laravel-vtu.voice_and_text.api_token');

        $this->client = $this->getInstance();
        $this->request = new Request('GET', $this->baseUrl);
    }


    /**
     * @inheritDoc
     */
    public function buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl): bool
    {
        try {
            $response = $this->client->send($this->request, [
                'query' => [
                    'token' => $this->username,
                    'command' => self::BUY_AIRTIME,
                    'amount' => $amount,
                    'mobileNumber' => $mobileNumber,
                    'mobileNetwork' => $mobileNetwork,
                    'callbackUrl' => $callBackUrl,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response['message'];

            return $response['status'] == '00' ? true : false;
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
    public function buyData($mobileNumber, $mobileNetwork, $dataPlan, $callbackUrl): bool
    {
        try {
            $response = $this->client->send($this->request, [
                'query' => [
                    'token' => $this->username,
                    'command' => self::BUY_DATA,
                    'mobileNumber' => $mobileNumber,
                    'mobileNetwork' => $mobileNetwork,
                    'dataPlan' => $dataPlan,
                    'CallBackUrl' => $callbackUrl,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = $response['message'];

            return $response['status'] == '00' ? true : false;
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
    public function payUtility($smartCardNumber, $cableTv, $package, $callbackUrl): bool
    {
        try {
            $response = $this->client->send($this->request, [
                'query' => [
                    'token' => $this->username,
                    'command' => self::PAY_UTILITY,
                    'smartCardNo' => $smartCardNumber,
                    'cableTv' => $cableTv,
                    'package' => $package,
                    'CallBackUrl' => $callbackUrl,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = array_key_exists($response['message'], $this->statusCodes) ? $this->statusCodes[$response['message']] : '';
            $this->orderId = $response['orderId'];

            return $response['status'] == '00' ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**Check order status
     * @param $orderId
     * @return bool
     */
    public function queryOrder($orderId)
    {
        try {
            $response = $this->client->send($this->request, [
                'query' => [
                    'token' => $this->username,
                    'command' => self::QUERY_ORDER,
                    'orderID' => $orderId,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = array_key_exists($response['status'], $this->statusCodes) ? $this->statusCodes[$response['status']] : '';
            $this->orderId = $response['orderId'];

            return $response ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }

    /**Cancel ongoing order
     * @param $orderId
     * @return bool
     */
    public function cancelOrder($orderId)
    {
        try {
            $response = $this->client->send($this->request, [
                'query' => [
                    'token' => $this->username,
                    'command' => self::CANCEL_ORDER,
                    'orderID' => $orderId,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response = array_key_exists($response['status'], $this->statusCodes) ? $this->statusCodes[$response['status']] : '';
            $this->orderId = $response['orderId'];

            return $response['status'] == 'ORDER_CANCELLED' ? true : false;
        } catch (ClientException $e) {
            $this->httpError = $e;

            return false;
        } catch (\Exception $e) {
            $this->httpError = $e;

            return false;
        }
    }
}
