<?php

namespace Tedakis\PumbleSDK;

use Illuminate\Support\ServiceProvider;

class PumbleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/pumble.php', 'pumble'
        );

        $this->app->singleton(PumbleService::class, function ($app) {
            $apiKey = config('pumble.api_key');

            if (empty($apiKey)) {
                throw new \RuntimeException(
                    'Pumble API key not configured. Please set PUMBLE_API_KEY in your .env file.'
                );
            }

            return new PumbleService($apiKey);
        });

        $this->app->alias(PumbleService::class, 'pumble');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/pumble.php' => config_path('pumble.php'),
            ], 'pumble-config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [PumbleService::class, 'pumble'];
    }
}
