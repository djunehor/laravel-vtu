<?php

namespace Djunehor\Vtu\Concrete;

use Djunehor\Vtu\Contracts\VtuServiceInterface;
use GuzzleHttp\Client;

abstract class Vtu implements VtuServiceInterface
{
    protected $text;
    protected $username;
    protected $password;
    protected $recipients = [];
    private static $httpClient;
    protected $sender;
    protected $response;
    protected $client;
    protected $request;

    /**
     * @var \Exception
     */
    public $httpError;

    /**We want HTTP CLient instantiated
     * only once in entire app lifecycle
     * @return Client
     */
    public static function getInstance()
    {
        if (! self::$httpClient) {
            self::$httpClient = new Client();
        }

        return self::$httpClient;
    }


    /**We want HTTP CLient instantiated
     * only once in entire app lifecycle
     * @return string $response
     */
    public function getResponse() : string
    {
        return $this->response;
    }

    public function getException() : \Exception
    {
        return $this->httpError;
    }

    public function getUsername()
    {
        return $this->username;
    }

}
