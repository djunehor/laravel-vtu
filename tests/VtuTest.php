<?php

namespace Djunehor\Vtu\Test;

use Djunehor\Vtu\Concrete\VoiceAndText;

class VtuTest extends TestCase
{

    public function testDefaultValue()
    {
        $vtu = new VoiceAndText();

        $this->assertEquals('123456789abcdeghi', $vtu->getUsername());
    }

    public function testSetValue()
    {
        $api = 'ajdkajwewoeowui23973uhw';
        $vtu = new VoiceAndText($api);
        $this->assertEquals($api, $vtu->getUsername());
    }


    public function testBuyAirtime()
    {
        // we want to test all SMS classes at once
        $smsClasses = scandir(__DIR__.'/../src/Concrete');

        foreach ($smsClasses as $class) {
            if ($class == '.' || $class == '..' || $class == 'Vtu.php') {
                continue;
            }
            $className = "\Djunehor\Vtu\Concrete\\".explode('.', $class)[0];
            $sms = new $className();

            $amount = 100;
            $mobileNumber = '08149659347';
            $mobileNetwork = '01';
            $callBackUrl = 'http://www.your-website.com';

            $send = $sms->buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl);

            $this->assertIsBool($send);
        }
    }
}
