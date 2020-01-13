<?php

if (! function_exists('buy_airtime')) {
    function buy_airtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl, $token = null, string $class = null)
    {
        $class = $class ? $class : config('laravel-vtu.default');
        $vtu = new $class($token);
        return $vtu->buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl);
    }
}
