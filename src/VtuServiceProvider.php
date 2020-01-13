<?php

namespace Djunehor\Vtu;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class VtuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Filesystem $filesystem
     * @return void
     */
    public function boot(Filesystem $filesystem)
    {
        $publishTag = 'laravel-vtu';
        if (app() instanceof \Illuminate\Foundation\Application) {
            $this->publishes([
                __DIR__.'/config/laravel-vtu.php' => config_path('laravel-vtu.php'),
            ], $publishTag);
        }
    }
}
