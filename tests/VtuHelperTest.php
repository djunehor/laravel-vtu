<?php

namespace Djunehor\Vtu\Test;

use Djunehor\Vtu\Concrete\VoiceAndText;

class VtuHelperTest extends TestCase
{
    private $vtu;

    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('laravel-vtu.default', VoiceAndText::class);
        $this->app['config']->set('laravel-vtu.voice_and_text.api_token', 'babalola');
    }

    public function testConfig()
    {
        $this->assertEquals('babalola', config('laravel-vtu.voice_and_text.api_token'));
        $this->assertEquals(VoiceAndText::class, config('laravel-vtu.default'));
    }

    public function testBuyAirtimeWithHelper()
    {
        $amount = 100;
        $mobileNumber = '08149659347';
        $mobileNetwork = '01';
        $callback = 'http://www.your-website.com';
        $sent = buy_airtime($amount, $mobileNumber, $mobileNetwork, $callback);

        $this->assertIsBool($sent);
    }

    public function testSendWithHelperSpecifyClass()
    {
        $amount = 100;
        $mobileNumber = '08149659347';
        $mobileNetwork = '01';
        $callback = 'http://www.your-website.com';
        $sent = buy_airtime($amount, $mobileNumber, $mobileNetwork, $callback, null, VoiceAndText::class);

        $this->assertIsBool($sent);
    }
}
