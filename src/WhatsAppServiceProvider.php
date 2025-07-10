<?php

namespace Digitslab\WhatsAppService;

use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config on vendor:publish
        $this->publishes([
            __DIR__ . '/../config/whatsapp.php' => config_path('whatsapp.php'),
        ], 'config');
    }

    public function register()
    {
        // Merge config so user can override
        $this->mergeConfigFrom(
            __DIR__ . '/../config/whatsapp.php',
            'whatsapp'
        );
    }
}
