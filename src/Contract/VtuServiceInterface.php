<?php
/**
 * Created by PhpStorm.
 * User: djunehor
 * Date: 13/01/2020
 * Time: 3:47 PM.
 */

namespace Djunehor\Vtu\Contracts;


/**
 * Interface VtuServiceInterface.
 */
interface VtuServiceInterface
{
    /**
     * @param $amount
     * @param $mobileNumber
     * @param $mobileNetwork
     * @param $callBackUrl
     * @return bool
     */
    public function buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl) : bool ;

    /**
     * @param $mobileNumber
     * @param $mobileNetwork
     * @param $dataPlan
     * @param $callBackUrl
     * @return $this | string
     */
    public function buyData($mobileNumber, $mobileNetwork, $dataPlan, $callBackUrl) : bool ;

    /**
     * @param $smartCardNumber
     * @param $cableTv
     * @param $package
     * @param $callBackUrl
     * @return bool
     */
    public function payUtility($smartCardNumber, $cableTv, $package, $callBackUrl) : bool ;

    /**
     * @return mixed
     */
    public function getResponse() : string;

    /**
     * @return \Exception|null
     */
    public function getException() : \Exception;

}
