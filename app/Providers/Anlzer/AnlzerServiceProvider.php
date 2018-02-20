<?php

namespace App\Providers\Anlzer;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as HttpClient;

class AnlzerServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AnlzerClient::class, function ($app) {
            $settings = new AnlzerServiceProviderSettings();
            $settings->BASE_URL = env('ANLZER_API_BASE_URL', 'http://localhost:8000');
            $client = new HttpClient();
            return new AnlzerClient($settings, $client);
        });
    }
}

class AnlzerServiceProviderSettings
{
    public $BASE_URL;
}