<?php

namespace Djunehor\Vtu\Test;

use Djunehor\Vtu\VtuServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Orchestra\Testbench\Concerns\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            VtuServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->app = $app;
        $smsConfig = include_once __DIR__ . '/../src/config/laravel-vtu.php';
        $app['config']->set('laravel-vtu', $smsConfig);
    }
}
